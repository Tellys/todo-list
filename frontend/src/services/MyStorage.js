class MyStorage {

    keyCript;

    store(label, data){
        localStorage.setItem(label);
        return;
    }

    get(label){
        return localStorage.getItem(label);
    }

    critp

}

export default new MyStorage();