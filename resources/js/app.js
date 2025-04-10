import './bootstrap';


document.addEventListener('DOMContentLoaded', function () {
    function disableSelectTabIndex() {
        document.querySelectorAll('.form-component-repeater [data-field-id] .choices__input').forEach(input => {
            input.tabIndex = -1;
        });
    }
    
    // Initial setup
    disableSelectTabIndex();
    
    // Re-run after Livewire updates
    document.addEventListener('livewire:update', disableSelectTabIndex);
    document.addEventListener('livewire:load', disableSelectTabIndex);
});