export default () => ({
    checkedAll: false,
    checkedKeys: [],
    init() {
        const wire = this.$wire;
        let rowCheckboxes = document.querySelectorAll('.rowCheckboxes');
        this.$watch('checkedAll', (checkedAll) => {
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
