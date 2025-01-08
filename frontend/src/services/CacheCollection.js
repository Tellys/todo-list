/**
 * Creates a collection of unique values in local storage
 * Works with arrays only
 * Singleton method to allow sharing between classes without adding extra stuff
 */
class CacheCollection {
    key;
    expire;
    data;
    items;

    constructor () {
        this.key = null;
        this.expire = null;
        this.data = null;
        this.items = null;
    }

    ///
    add(key, data, expire=null) {
        this.key = key.toString();
        this.expire = expire ?? process.env.VUE_APP_TIME_TO_EXPIRES_SESSION;
        this.data = JSON.stringify(data);
        localStorage.setItem(this.key+'_ts', Date.now());
        localStorage.setItem(this.key, this.data);
        return;
    }

    get(key){
        this.key = key
        // if (this.isExpired()) {
        //     return null;
        // }

        //console.log('localStorage.getItem(key)',JSON.parse(localStorage.getItem(this.key)));
        return JSON.parse(localStorage.getItem(this.key));
    }

    // async get(key) {
    //     return new Promise((resolve, reject) => {
    //       var value = localStorage.getItem(key);
    //       if (value === null) {
    //         reject(null);
    //       } else {
    //         resolve(value);
    //       }
    //     });
    //   }

    remove(key){
        this.key = key
        this.clear();
        return
    }

    isExpired(){
        console.log('asdsadasdasdasda',
            localStorage.getItem(this.key),
            localStorage.getItem(this.key+'_ts')
        );
        let whenCached = localStorage.getItem(this.key + '_ts')
        let age = (Date.now() - whenCached) / 1000
        console.log('age', age)
        console.log('Date.now() - whenCached', Date.now(), whenCached)
        console.log('[CachedColletion] is Expired', age>this.expire );
        if (age > this.expire) {
            this.clear();
            return true;
        } else {
            return false;
        }
    }
    
    clear(){
        localStorage.removeItem(this.key)
        localStorage.removeItem(this.key + '_ts')
        return
    }

    // del(el) {
    //     let idx = this.indexOf(el)
    //     if (idx !== -1) {
    //         this.items.splice(idx,1);
    //     }
    //     this.store();
    // }
    // has(el) {
    //     return this.indexOf(el) !== -1;
    // }
    // indexOf(el) {
    //     el = el.toString() || el;
    //     return this.items.indexOf(el);
    // }
    // store() {
    //     localStorage.setItem(this.key, JSON.stringify(this.items));
    //     localStorage.setItem(this.key+'_ts', Date.now());
    // }

    // getall() {
    //     let  stored = localStorage.getItem(this.key);
    //     console.log(stored);
    //     return (this.isExpired() || !stored) ?
    //     [] :
    //     JSON.parse(stored);
    // }
    // clear() {
    //     localStorage.removeItem(this.key)
    //     localStorage.removeItem(this.key + '_ts')
    // }
}
/**
 * Caches objects as singletons. Alternative, can ignore this.items and just load
 * always from localStorage.
 * @type {Object}
 */

// let cache = {};
// // [getSingleton description]
// // @method getSingleton
// // @param  {string}     key           Key for localStorage
// // @param  {Number}     [expire=1200] Expiration time in seconds
// // @param  {String}     [sep=',']     separator in case. Default: ,
// // @return {CacheCollection}          A singleton of CacheCollection

// function getSingleton(key, expire=1200) {
//     if (!cache.hasOwnProperty(key)) {
//         // console.log('[getSingleton] Not in cache');
//         cache[key] = new CacheCollection(key, expire);
//     }
//     // console.log('[getSingleton]', cache[key]);
//     return cache[key];
// }
// export {getSingleton, CacheCollection}; 
export default new CacheCollection();