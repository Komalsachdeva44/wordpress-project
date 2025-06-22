jQuery(function($) {
    let timer = null;

    $('#ecwid-search-input').on('input', function() {
        clearTimeout(timer);
        const q = $(this).val().trim();

        if (q.length < ecwidSearch.minChars) {
            $('#ecwid-search-results').empty();
            return;
        }

        timer = setTimeout(() => {
            $.get(ecwidSearch.ajaxUrl, { action: 'ecwid_search', q: q }, function(response) {
                let html = '';
                if (response.success && response.data.length) {
                    response.data.forEach(item => {
                        html += `
                            <a href="${item.url}" class="ecwid-res-item">
                                <img src="${item.image}" width="60" height="60">
                                <div class="ecwid-res-info">
                                    <span class="res-name">${item.name}</span>
                                    <span class="res-price">${item.price}</span>
                                </div>
                            </a>`;
                    });
                } else {
                    html = '<div class="ecwid-no-res">No products found.</div>';
                }
                $('#ecwid-search-results').html(html);
            });
        }, 300);
    });
});
