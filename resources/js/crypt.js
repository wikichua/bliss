import CryptoJS from "crypto-js";

import * as serialize from 'php-serialize';

const LaravelCrypt = function () {
    this.key = CryptoJS.enc.Base64.parse(import.meta.env.VITE_APP_KEY.substr(7));
}

LaravelCrypt.prototype.decrypt = function (encryptStr) {
    let str = JSON.parse(atob(encryptStr)),
        iv = CryptoJS.enc.Base64.parse(str.iv);

    return serialize.unserialize(
        CryptoJS.AES.decrypt(str.value, this.key, {
            iv: iv,
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }).toString(CryptoJS.enc.Utf8)
    );
};

LaravelCrypt.prototype.encrypt = function (data) {
    let iv = CryptoJS.lib.WordArray.random(16);
    let encrypted = CryptoJS.AES.encrypt(data, this.key, {
        iv: iv,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    }).toString();

    iv = CryptoJS.enc.Base64.stringify(iv);

    let result = {
        iv: iv,
        value: encrypted,
        mac: CryptoJS.HmacSHA256(iv + encrypted, this.key).toString()
    }

    result = CryptoJS.enc.Utf8.parse(JSON.stringify(result));

    return CryptoJS.enc.Base64.stringify(result);
};

export const Crypt = window.Crypt = new LaravelCrypt();
