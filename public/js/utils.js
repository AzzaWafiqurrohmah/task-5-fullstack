function displayFormErrors(errors) {
    for(const [key, value] of Object.entries(errors)) {
        const input = $(`#${key}`);

        if (input.attr('type') === 'file') {
            const parent = input.parent();
            parent.addClass('is-invalid');
            parent.next().html(value[0]);
        } else if (input.attr('type') === 'password') {
            input.addClass('is-invalid');
            input.nextAll('div').first().html(value[0]);
        } else {
            input.addClass('is-invalid');
            input.next().html(value[0]);
        }

    }
}

function removeFormErrors() {
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').html('');
}

function fillFormdata(data) {
    console.log(data);
    for(const [key, value] of Object.entries(data))
        $(`#${key}`).val(value);
}