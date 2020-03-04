window.onload = () => {
    let form = document.forms.filter;
    setModelOptions(form.mark.value);

    form.mark.onchange = (e) => {
        setModelOptions(e.target.value)
    };


    form.zero.onclick = () => {
        form.year.value = ""
        form.mark.value = ""
        form.model.value = ""
    };

    function setModelOptions(a) {
        options = Array.from(document.getElementsByClassName(a));
        form.model.innerHTML = "<option disabled selected >Modeli secin</option>";
        options.forEach(el => {
            selected = el.getAttribute('selected') ?? '';
            form.model.innerHTML += `<option ${selected} value="${el.value}">${el.innerHTML}</option>`
        });
    }
};



