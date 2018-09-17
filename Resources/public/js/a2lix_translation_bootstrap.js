$(function() {
    $('ul.canabelle_cms_translationsLocales').on('click', 'a', function(evt) {
        evt.preventDefault();
        $(this).tab('show');
    });

    $('div.canabelle_cms_translationsLocalesSelector').on('change', 'input', function(evt) {
        var $tabs = $('ul.canabelle_cms_translationsLocales');

        $('div.canabelle_cms_translationsLocalesSelector').find('input').each(function() {
            $tabs.find('li:has(a[data-target=".canabelle_cms_translationsFields-' + this.value + '"])').toggle(this.checked);
        });

        $('ul.canabelle_cms_translationsLocales li:visible:first').find('a').tab('show');
    }).trigger('change');
});
