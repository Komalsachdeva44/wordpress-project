jQuery(function($){
    const minChars = ecwidSearch.minChars;
    let timer;

    function performSearch(q){
        // Show loading state
        $('#ecwid-search-results').html('<div class="ecwid-loading">Searching...</div>').show();

        $.get(ecwidSearch.ajaxUrl, { action: 'ecwid_search', q: q }, res => {
            let html = '';
            if(res.success && res.data.length){
                const filtered = res.data.filter(item =>
                    item.name.toLowerCase().includes(q.toLowerCase())
                );
                if(filtered.length){
                    filtered.forEach(item => {
                        html += `<a href="${item.url}" class="ecwid-res-item">
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
            } else {
                html = '<div class="ecwid-no-res">No products found.</div>';
            }

            // Fade in the results
            $('#ecwid-search-results').hide().html(html).fadeIn(200);
        });
    }

    $('#ecwid-search-input').on('input', function(){
        clearTimeout(timer);
        const term = $(this).val().trim();
        if(term.length < minChars){
            $('#ecwid-search-results').empty().show();
        } else {
            timer = setTimeout(() => performSearch(term), 250);
        }
    });

    $('#ecwid-search-btn').click(function(){
        const term = $('#ecwid-search-input').val().trim();
        if(term.length >= minChars) {
            performSearch(term);
        }
    });

    $(document).on('click', '.ecwid-pop-item', function(e){
        e.preventDefault();
        const t = $(this).text();
        $('#ecwid-search-input').val(t);
        performSearch(t);
    });
});
 