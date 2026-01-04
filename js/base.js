function mcColorToHtml(text) {
    const colors = {
        '0': '#000000',
        '1': '#0000AA',
        '2': '#00AA00',
        '3': '#00AAAA',
        '4': '#AA0000',
        '5': '#AA00AA',
        '6': '#FFAA00',
        '7': '#AAAAAA',
        '8': '#555555',
        '9': '#5555FF',
        'a': '#55FF55',
        'b': '#55FFFF',
        'c': '#FF5555',
        'd': '#FF55FF',
        'e': '#FFFF55',
        'f': '#FFFFFF'
    };

    let result = '';
    let openSpan = false;

    for (let i = 0; i < text.length; i++) {
        if (text[i] === '§') {
            const code = text[i + 1]?.toLowerCase();

            // Reset
            if (code === 'r') {
                if (openSpan) {
                    result += '</span>';
                    openSpan = false;
                }
                i++;
                continue;
            }

            // Color code
            if (colors[code]) {
                if (openSpan)
                    result += '</span>';
                result += `<span style="color:${colors[code]}">`;
                openSpan = true;
                i++;
                continue;
            }
        }

        // Escape HTML characters
        result += text[i]
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
    }

    if (openSpan)
        result += '</span>';

    return result;
}