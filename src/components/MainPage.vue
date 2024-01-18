<template>
    <div class="modal">
        <div class="modal-content" :style="{ height: isLoginPage ? '55.2vh' : '65.2vh' }">
            <div v-if="isLoginPage" class="login">
                <h1>Login</h1>
                <form action="">
                    <div class="email">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="yourmail@yey-team.com">
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="******************">
                    </div>
                </form>
                <button>Login</button>
                <p @click="isLoginPage = false">No Account ?</p>
            </div>
            <div v-else class="register">
                <h1>Register</h1>
                <form action="">
                    <div class="informations">
                        <div class="firstname">
                            <label for="firstname">Firstname</label>
                            <input type="text" name="firstname" id="firstname" placeholder="Johnny">
                        </div>
                        <div class="lastname">
                            <label for="lastname">Lastname</label>
                            <input type="text" name="lastname" id="lastname" placeholder="Sins">
                        </div>
                    </div>
                    <div class="email">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="yourmail@yey-team.com">
                    </div>
                    <div class="password">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="******************">
                    </div>
                </form>
                <button>Register</button>
                <p @click="isLoginPage = true">You already have an account ?</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const isLoginPage = ref(true);
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
</style>
  