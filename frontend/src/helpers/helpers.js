class helpers {

    encodeHTML(s) {
        return s.replaceAll(/&/g, '%26')
            .replaceAll(/</g, '%3C')
            .replaceAll(/>/g, '%3E')
            .replaceAll(/=/g, '%3D')
            .replaceAll(/!/g, '%21')
            .replaceAll(/\?/g, '%3F')
            .replaceAll(/"/g, '%22')
            .replaceAll(/'/g, '%27')
            .replaceAll(/\//g, '%2F')
            .replaceAll(/\\/g, '%5C')
            ;
    }

    encodeID(s) {
        if (s === '') return '_';
        return s.replace(/[^a-zA-Z0-9.-]/g, function (match) {
            return '_' + match[0].charCodeAt(0).toString(16) + '_';
        });
    }

    async getGeolocation() {
        return new Promise((resolve, reject) => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const latitude = position.coords?.latitude ?? null;
                        const longitude = position.coords?.longitude ?? null;
                        const accuracy = position.coords?.accuracy ?? null;
                        const altitude = position.coords?.altitude ?? null;
                        const speed = position.coords?.speed ?? null;
                        resolve({latitude,longitude, accuracy, altitude, speed});
                    },
                    (error) => {
                        reject(error);
                    }
                );
            } else {
                reject(false);
            }
        });
    }

    async copyToClipboard(text) {

        return new Promise((resolve, reject) => {
            if (typeof navigator !== "undefined" && typeof navigator.clipboard !== "undefined" && navigator.permissions !== "undefined") {
                const type = "text/plain";
                const blob = new Blob([text], { type });
                const data = [new ClipboardItem({ [type]: blob })];
                navigator.permissions.query({ name: "clipboard-write" }).then((permission) => {
                    if (permission.state === "granted" || permission.state === "prompt") {
                        navigator.clipboard.write(data)
                            .then(() => {
                                return resolve(true);
                                //return true;
                            }).catch(() => {
                                return reject(false);
                                //return false;
                            });
                    }
                    else {
                        return reject(new Error("Permission not granted!"));
                    }
                });
            }
            else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                var textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed";
                textarea.style.width = '2em';
                textarea.style.height = '2em';
                textarea.style.padding = 0;
                textarea.style.border = 'none';
                textarea.style.outline = 'none';
                textarea.style.boxShadow = 'none';
                textarea.style.background = 'transparent';
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    document.execCommand("copy");
                    document.body.removeChild(textarea);
                    return resolve();
                }
                catch (e) {
                    document.body.removeChild(textarea);
                    return reject(e);
                }
            }
            else {
                return reject(new Error("None of copying methods are supported by this browser!"));
            }
        });
    }

    ///
    showMaxWordsIntoTag(words, maxLenght, tagId = null){
        let r = null;
        let d = document.getElementById(tagId)

        if (words.length <= maxLenght) {
            return d.innerHTML(words);
        }

        let w = words.substring(0, maxLenght);

        if (tagId) {


            if (d.style.display === "none") {
                r = words+'<i class="bi bi-caret-right-fill"></i>';
                d.style.display = "block";
              } else {
                r = w+'<i class="bi bi-caret-down-fill"></i>';
                d.style.display = "none";
              }
        }
        
        return d.innerHTML(r);

    }
}

export default new helpers();

//        <h1>Number format :  {{ $numFormat(100000) }}</h1>