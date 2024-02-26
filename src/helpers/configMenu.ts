export let configsMenus = {
    mainMenu : [
        {
            "type": "leave",
            "icon": "",
            "name": "leave",
            "action": "closeMenu"
        },
        {
            "type": "addBlock",
            "icon": "",
            "name": "Add block ...",
            "action": "openMenuAddBlock"
        },
        {
            "type": "removeBlock",
            "icon": "",
            "name": "remove",
            "action": "closeMenu"
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