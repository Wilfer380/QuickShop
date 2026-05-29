const userToggle = document.getElementById('btn_option_users');
const userMenu = document.getElementById('select_option_users');
const cartToggle = document.getElementById('btn_option_shop');
const cartMenu = document.getElementById('select_option_shop');

const toggleMenu = (menu) => {
    if (!menu) {
        return;
    }

    menu.classList.toggle('hidden');
};

userToggle?.addEventListener('click', () => {
    toggleMenu(userMenu);
    cartMenu?.classList.add('hidden');
});

cartToggle?.addEventListener('click', () => {
    toggleMenu(cartMenu);
    userMenu?.classList.add('hidden');
});

document.addEventListener('click', (event) => {
    if (userMenu && userToggle && !userMenu.contains(event.target) && !userToggle.contains(event.target)) {
        userMenu.classList.add('hidden');
    }

    if (cartMenu && cartToggle && !cartMenu.contains(event.target) && !cartToggle.contains(event.target)) {
        cartMenu.classList.add('hidden');
    }
});
