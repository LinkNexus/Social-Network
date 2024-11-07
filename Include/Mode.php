<?php if (App::getSession()->getKey('mode') == 1): ?>
    <style>
        ::-webkit-scrollbar-thumb{
            background: dodgerblue;
        }
    </style>
<?php endif; ?>
<script>
    let body = document.querySelector('body'),
        buttons = document.querySelectorAll('a, button'),
        inputFields = document.querySelectorAll('input'),
        labels = document.querySelectorAll('label'),
        mode = <?= App::getSession()->getKey('mode') == 1 ? '1' : '0' ?>,
        hover = 0,
        focus = 0;

    console.log(buttons.length);

    if (mode == 1) {
        body.style.background = "url('Assets/Image2.jpg')";

        for (let button of buttons){
            button.style.color = 'dodgerblue';

            button.addEventListener('mouseover', function (evt) {
                evt.stopPropagation();
                hover++;
                button.style.color = 'white';

                if (hover % 2 !== 1){
                    button.style.background = 'dodgerblue';
                    button.style.boxShadow = '0 0 5px dodgerblue,\n' +
                        '0 0 25px dodgerblue,\n' +
                        '0 0 50px dodgerblue,\n' +
                        '0 0 100px dodgerblue';
                } else {
                    button.style.background = 'violet';
                    button.style.boxShadow = '0 0 5px violet,\n' +
                        '0 0 25px violet,\n' +
                        '0 0 50px violet,\n' +
                        '0 0 100px violet';
                }
            })

            button.addEventListener('mouseleave', function (evt) {
                evt.stopPropagation();
                button.style.background = 'none';
                button.style.boxShadow = 'none';

                for (let i = 0; i < buttons.length; i++) {
                    if (hover % 2 !== 1) {
                        buttons[i].style.color = 'violet';
                    } else {
                        buttons[i].style.color = 'dodgerblue';
                    }
                }
            })
        }

        for (let i = 0; i < inputFields.length; i++){
            inputFields[i].addEventListener('focus', function () {
                focus++;
                if (focus % 2 !== 1){
                    labels[i].style.color = 'dodgerblue';
                } else {
                    labels[i].style.color = 'violet';
                }
                console.log(focus);
            })

            inputFields[i].addEventListener('focusout', function () {
                labels[i].style.color = 'white';
            })
        }
    }
</script>

