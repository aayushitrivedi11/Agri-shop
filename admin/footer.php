</div>
    <script>
        // Modal control
        const modals = document.querySelectorAll('.modal');
        const openModalButtons = document.querySelectorAll('.open-modal');
        const closeButtons = document.querySelectorAll('.close');

        openModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-modal');
                document.getElementById(modalId).style.display = 'flex';
            });
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                button.closest('.modal').style.display = 'none';
            });
        });

        window.addEventListener('click', (event) => {
            modals.forEach(modal => {
                if (event.target == modal) modal.style.display = 'none';
            });
        });
    </script>
</body>
</html>