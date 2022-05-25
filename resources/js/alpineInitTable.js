export default () => ({
    checkedAll: false,
    checkedKeys: [],
    init() {
        const wire = this.$wire;
        this.$watch('checkedAll', (checkedAll) => {
            let rowCheckboxes = document.querySelectorAll('.rowCheckboxes');
            this.checkedKeys = [];
            _.forEach(rowCheckboxes, (checkbox) => {
                checkbox.checked = checkedAll;
                if (checkbox.checked) {
                    this.checkedKeys.push(checkbox.value);
                }
            });
        });
    },
})
