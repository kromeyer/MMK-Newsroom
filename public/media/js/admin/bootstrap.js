
/**
 * Author       Patrick Kromeyer
 * License      please view LICENSE file
 */

$(document).ready(function() {
    $('a:not([href^="/"])').attr('target', '_blank');

    $('a.confirm').click(function (event) {
        event.stopPropagation();

        return confirm('Sicher?');
    });

    $('.message').delay(3000).slideUp('slow');

    $('input[type=file][disabled]').each(function (index) {
        var self = this;
        var toggleLink = $('<a>ersetzen oder löschen</a>');

        toggleLink.click(function (event) {
            event.preventDefault();

            var inputFile = $(self);

            if (inputFile.attr('disabled'))
            {
                inputFile.removeAttr('disabled');
                $(this).html('nicht ersetzen oder löschen');
            }
            else
            {
                inputFile.attr('disabled', 'disabled');
                $(this).html('ersetzen oder löschen');
            }
        });

        $('label[for=' + this.id + ']').append(' ').append(toggleLink);
    });
});
