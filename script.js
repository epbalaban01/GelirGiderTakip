// Fonksiyon: Sayıyı formatla (binlik ayırıcı ekle ve ₺ sembolü ekle)
function formatNumber(input) {
    let value = input.value.replace(/[^0-9,]/g, '');

    // Eğer kullanıcı virgülden sonraki kısmı yazmadıysa ",00" ekle
    if (!value.includes(',')) {
        value += ',00';
    }

    // Mevcut virgüllü değeri ayır
    let parts = value.split(',');

    // Binlik ayırıcı ekle
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    // Formatlanmış değeri geri yaz ve ₺ sembolünü ekle
    input.value = parts.join(',') + " ₺";
}

// Fonksiyon: Miktarı temizle (Noktaları ve ₺ sembolünü kaldır)
function cleanAmount(input) {
    let cleanValue = input.value.replace(/[.₺]/g, '').replace(',', '.');
    input.value = cleanValue;
}
