/**
 *
 * @type {{version: number, hideFlashback: Function, showRequired: Function, showFormErrors: Function, iCheck: Function}}
 */

var cs = {
    version: 1.0,

    //Remueve los avisos después de un intervalo de tiempo.
    hideFlashback: function () {
        window.setTimeout(function () {
            $('.alert').slideUp(250, function () {
                this.remove()
            });
        }, 4000);
    },

    //Muestra un asterisco en el label de un campo obligatorio.
    showRequired: function () {
        $('label').each(function () {
            var $this = $(this);
            var target = $this.attr('for');
            if (target && $('#' + target).attr('required')) {
                var text = $this.text();
                text = text + ' * ';
                $this.text(text);
            }
        });
    },

    //Muestra los errores de un formulario formulario.
    //parenid: id del formulario.
    //selectors: selectores de los campos que se desea inspeccionar.
    //placement: posición del tip.
    showFormErrors: function (parentid, selectors, placement) {

        if (typeof parentid == 'string')
            parentid = $(parentid);

        if (!placement) placement = 'bottom';
        for (j in selectors) {
            parentid.find(selectors[j]).each(function () {
                var $this = $(this);
                var error = $this.attr('error');
                if (typeof error == 'string') {
                    error = error.trim();
                    if (error != '') {
                        $this.tooltip({
                            title: error,
                            trigger: 'focus',
                            placement: placement
                        }).keypress(function () {
                            $this.unbind('keypress')
                                .tooltip('destroy').parent().removeClass('has-error');
                        }).parent().addClass('has-error');
                    }
                }
            });
        }
    },

    iCheck: function (parent) {
        if ($.fn.iCheck) {
            if (typeof parent == 'string'){
                parent = $(parent);
            }
            parent.find('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-grey',
                radioClass: 'iradio_flat-grey'
            }).css('visible', true);

        }
    },

    //Impide que se inserten determinados valores en un input.
    input: function (target, type) {
        switch (type) {
            case 'integer':
                $(target).keypress(function (e) {
                    if (!cs.isNumberChar(e.charCode) && !cs.isEditKey(e.keyCode))
                        e.preventDefault();
                });
                break;
            case 'decimal':
                $(target).keypress(function (e) {
                    var dot = 46 == e.charCode && -1 === $(target).val().toString().indexOf('.');
                    if (!cs.isNumberChar(e.charCode) && !cs.isEditKey(e.keyCode) && !dot )
                        e.preventDefault();
                });
                break;
        }
    },
    isEditKey: function (keyCode) {

        switch (keyCode) {
            case 8:
            case 9:
            case 37:
            case 39:
            case 116:
            case 13:
                return true;
            default:
                return false;
        }
    },
    isNumberChar: function (charCode) {
        return charCode >= 48 && charCode <= 57;
    },

};

