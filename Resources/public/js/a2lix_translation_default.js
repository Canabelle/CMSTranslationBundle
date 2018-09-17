$(function() {
    $('ul.canabelle_cms_translationsLocales').on('click', 'a', function(evt) {
        evt.preventDefault();
        var target = $(this).attr('data-target');
        $('li:has(a[data-target="' + target + '"]), div' + target, 'div.canabelle_cms_translations').addClass('active')
            .siblings().removeClass('active');
    });

    $('div.canabelle_cms_translationsLocalesSelector').on('change', 'input', function(evt) {
        var $tabs = $('ul.canabelle_cms_translationsLocales');

        $('div.canabelle_cms_translationsLocalesSelector').find('input').each(function() {
            $tabs.find('li:has(a[data-target=".canabelle_cms_translationsFields-' + this.value + '"])').toggle(this.checked);
        });

        $('ul.canabelle_cms_translationsLocales li:visible:first').find('a').trigger('click');
    }).trigger('change');
});
