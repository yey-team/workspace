export let configsMenus = {
    mainMenu : [
        {
            "type": "addBlock",
            "icon": "",
            "name": "Add block ...",
            "action": "openMenuAddBlock"
        }
    ],
    addBlockMenu : [
        {
            "type": "addBlockText",
            "icon": "",
            "name": "Add block text",
            "action": "addBlockText"
        },
        {
            "type": "addBlockImage",
            "icon": "",
            "name": "Add block image",
            "action": "addBlockImage"
        }
    ]
}

export default { configsMenus }