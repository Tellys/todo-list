class Masks {

    tokens = {
        '#': { pattern: /\d/ },
        'n': { pattern: /[0-9]/ },
        'N': { pattern: /[0-9]/ },
        'X': { pattern: /[0-9a-zA-Z]/ },
        'S': { pattern: /[a-zA-Z]/ },
        'A': { pattern: /[a-zA-Z]/, transform: v => v.toLocaleUpperCase() },
        'a': { pattern: /[a-zA-Z]/, transform: v => v.toLocaleLowerCase() },
        '!': { escape: true }
    }

    init(key, value) {

        if (!value || !key) {
            //console.log(key, 'undefined')
            return false
        }

        let v = value.toString()

        if (Array.isArray(key)) {
            return this.dynamicMask(v, key);
        }

        if (this[key]) {
            //console.log(key, '>>> achou', 'valor =', value);
            return this[key](v);
        }

        //console.log(key, 'nao achou');
        return this.maskit(v, key);
    }

    dynamicMask(value, masks, masked = true) {
        masks = masks.sort((a, b) => a.length - b.length)
        var i = 0
        while (i < masks.length) {
            var currentMask = masks[i]
            i++
            var nextMask = masks[i]
            if (!(nextMask && this.maskit(value, nextMask, true).length > currentMask.length)) {
                return this.maskit(value, currentMask, masked)
            }
        }
        return '' // empty masks
    }

    maskit(value, mask, masked = true, tokens = this.tokens) {
        value = value || ''
        mask = mask || ''
        var iMask = 0
        var iValue = 0
        var output = ''
        while (iMask < mask.length && iValue < value.length) {
            var cMask = mask[iMask]
            var masker = tokens[cMask]
            var cValue = value[iValue]

            if (masker && !masker.escape) {
                if (masker.pattern.test(cValue)) {
                    output += cValue
                    iMask++
                }
                iValue++
            } else {
                if (masker && masker.escape) {
                    iMask++ // take the next mask char and treat it as char
                    cMask = mask[iMask]
                }
                if (masked) output += cMask
                if (cValue === cMask) iValue++ // user typed the same char
                iMask++
            }
        }

        // fix mask that ends with a char: (#)
        var restOutput = ''
        while (iMask < mask.length && masked) {
            cMask = mask[iMask]
            if (tokens[cMask]) {
                restOutput = ''
                break
            }
            restOutput += cMask
            iMask++
        }

        return output + restOutput
    }

    rollbackMask(value, mask, masked = true, tokens = this.tokens) {

        let v = value.toString()

        if (Array.isArray(mask)) {
            return this.dynamicRollbackMask(v, mask);
        }

        return this.funcRollbackMask(v, mask, masked, tokens) 
    }

    funcRollbackMask(value, mask, masked = true, tokens = this.tokens) {
        value = value || ''
        mask = mask || ''
        var iMask = 0
        var iValue = 0
        var output = ''

        while (iMask < mask.length && iValue < value.length) {
            var cMask = mask[iMask]
            var masker = tokens[cMask]
            var cValue = value[iValue]
            if (masker && !masker.escape) {
                if (masker.pattern.test(cValue)) {
                    output += cValue
                    iMask++
                }
                iValue++
            }
            else {
                iMask++
            }
        }

        // fix mask that ends with a char: (#)
        var restOutput = ''
        while (iMask < mask.length && masked) {
            cMask = mask[iMask]
            if (tokens[cMask]) {
                restOutput = ''
                break
            }
            restOutput += cMask
            iMask++
        }

        return output + restOutput
    }

    dynamicRollbackMask(value, masks, masked = true) {
        masks = masks.sort((a, b) => a.length - b.length)
        var i = 0
        while (i < masks.length) {
            var currentMask = masks[i]
            i++
            var nextMask = masks[i]
            if (!(nextMask && this.funcRollbackMask(value, nextMask, true).length > currentMask.length)) {
                return this.funcRollbackMask(value, currentMask, masked)
            }
        }
        return '' // empty masks
    }

    onlyMumbers(v) {
        return v.toString().replace(/[^\d]+/g, '')
    }
}

export default new Masks();