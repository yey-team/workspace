<template>
    <div class="modal">
        <div class="modal-content" :style="{ height: isLoginPage ? '55.2vh' : '65.2vh' }">
            <div v-if="isLoginPage" class="login">
                <h1>Login</h1>
                <form @submit.prevent="sendData('login')">
                    <div class="email">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="yourmail@yey-team.com">
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="******************">
                    </div>
                    <p id="errorPlaceHolder"></p>
                    <button type="submit">Login</button>
                </form>
                <p @click="isLoginPage = false">No Account ?</p>
            </div>
            <div v-else class="register">
                <h1>Register</h1>
                <form @submit.prevent="sendData('register')">
                    <div class="informations">
                        <div class="firstname">
                            <label for="firstname">Firstname</label>
                            <input type="text" name="firstname" id="firstname" placeholder="Steve">
                        </div>
                        <div class="lastname">
                            <label for="lastname">Lastname</label>
                            <input type="text" name="lastname" id="lastname" placeholder="Persson">
                        </div>
                    </div>
                    <div class="email">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="steve@mail.com">
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="******************">
                    </div>
                    <p id="errorPlaceHolder"></p>
                    <button type="submit">Register</button>
                </form>
                <p @click="isLoginPage = true">You already have an account ?</p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, defineEmits } from 'vue';
const isLoginPage = ref(true);

const emits = defineEmits(['loginSuccess']);

function checkSocket() {
    const token = localStorage.getItem('token');


    if (token !== null) {
        const url = `https://workspace.yey-team.com/api/api.php?tokenCheck=true&token=${token}`;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP Error: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data && data.error) {
                    showErrorLogin(data.error)
                } else if (data.status == "invalid-token") {
                    return
                } else if (data.status == "valid-connection") {
                    emits('loginSuccess');
                    return data
                }
            })
            .catch(error => {
                console.error('Error during the request:', error);
                showErrorLogin('Error during login. Please try again.');
            });
    } else {
        return
    }
}

function createSocket(user_id: string) {
    const url = `https://workspace.yey-team.com/api/api.php?tokenCreation=true&user_id=${user_id}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            localStorage.setItem('token', data.token);
            // User Informations
        })
        .catch(error => {
            console.error('Error during the request:', error);
            showErrorLogin('Error during login. Please try again.');
        });
}


function getInformation(type: string) {
    let informations = [];
    if (type === 'login') {
        informations.push((document.querySelector('.login #email') as HTMLInputElement).value)
        informations.push((document.querySelector('.login #password') as HTMLInputElement).value)
        return informations
    } else if (type === 'register') {
        informations.push((document.querySelector('.register #firstname') as HTMLInputElement).value)
        informations.push((document.querySelector('.register #lastname') as HTMLInputElement).value)
        informations.push((document.querySelector('.register #email') as HTMLInputElement).value)
        informations.push((document.querySelector('.register #password') as HTMLInputElement).value)
        return informations
    }
    return undefined
}

function sendData(type: string) {
    showErrorLogin('')
    if (type == "login") {
        let result = getInformation(type);

        if (result){
            const url = `https://workspace.yey-team.com/api/api.php?login=true&email=${result[0]}&password=${result[1]}`;
    
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP Error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.error) {
                        showErrorLogin(data.error)
                    } else if (data.status) {
                        createSocket(data.user["user-id"])
                        emits('loginSuccess');
                        console.log('Can Log')
                    }
                })
                .catch(error => {
                    console.error('Error during the request:', error);
                    showErrorLogin('Error during login. Please try again.');
                });
        }

    } else if (type == "register") {
        let result = getInformation(type);

        if (result){

            const url = `https://workspace.yey-team.com/api/api.php?register=true&firstname=${result[0]}&lastname=${result[1]}&email=${result[2]}&password=${result[3]}`;
    
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP Error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.error) {
                        showErrorLogin(data.error)
                    } else if (data.status) {
                        createSocket(data.user_id)
                        emits('loginSuccess');
                        console.log('Can Log')
                    }
                })
                .catch(error => {
                    console.error('Error during the request:', error);
                    showErrorLogin('Error during login. Please try again.');
                });
        }
    }

}

function showErrorLogin(message: string) {
    const errorPlaceHolder = document.querySelector('#errorPlaceHolder');
    if (errorPlaceHolder){
        errorPlaceHolder.innerHTML = message;
    }
}

checkSocket()
</script>
  
<style scoped>
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.modal-content {
    font-family: Inter;
    background-color: var(--main-div-color);
    width: 35.33vw;
    height: 55.2vh;
    border-radius: 1.5vw;
    border: 1px solid var(--border-color);
    box-shadow: 0px 10px 10px 0px rgba(0, 0, 0, 0.25);
}

.modal-content h1 {
    color: var(--main-text-color);
    text-align: center;
    font-family: Inter;
    font-size: 1.5vw;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-top: 5vh;
}

.modal-content form {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.modal-content form div {
    display: flex;
    flex-direction: column;
}

.modal-content form label {
    margin-top: 3vh;
    margin-bottom: 1.5vh;
    margin-left: 0.5vw;
    font-family: Inter;
    font-size: 1.3vw;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.modal-content input {
    color: var(--main-text-color);
    border-radius: 1.5vw;
    background-color: var(--second-div-color);
    border: none;
    font-size: 1.1vw;
    width: 18vw;
    padding-left: 2vw;
    padding-right: 2vw;
    padding-top: 1vh;
    padding-bottom: 1vh;
    font-family: Inter;
}

.modal-content button {
    display: block;
    margin: auto;
    font-family: Inter;
    color: var(--main-text-color);
    border-radius: 1.5vw;
    padding-right: 2.5vw;
    padding-left: 2.5vw;
    padding-top: 0.5vw;
    padding-bottom: 0.5vw;
    margin-top: 7vh;
    font-size: 1.2vw;
    background-color: var(--accent-color);
}

.modal-content p {
    margin-top: 2vh;
    color: var(--accent-color);
    font-size: 0.8vw;
    text-align: center;
    font-family: Inter;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    cursor: pointer;
}

.modal-content {
    height: 65.2vh;
}

.modal-content .informations {
    width: 26.5vw;
    flex-direction: row;
    justify-content: space-evenly;
}

.modal-content .informations input {
    width: 6vw;
}

.modal-content #errorPlaceHolder {
    position: absolute;
    margin-top: 20vh;
    font-size: 1vw;
    color: red;
}
</style>
  