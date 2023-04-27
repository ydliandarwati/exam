const buttonElement = document.getElementById('menu-button')
const menuElement = document.getElementById('menu')
const openMenuCalssName = 'open'

// toggle menu function for open and close the menu
function toggleMenu () {
    const menuOpened = menuElement.classList.contains(openMenuCalssName)
    if(!menuOpened) {
        menuElement.classList.add(openMenuCalssName)
    } else {
        menuElement.classList.remove(openMenuCalssName)
    }
}

// bind event on toggle menu button
buttonElement.addEventListener('click', toggleMenu)