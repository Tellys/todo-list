import scroll from "./scroll";

class Validation {

  // my form object
  myForm;

  // fields of form + values
  fields;

  // rules of array
  rules;

  // active rule of object
  rule;

  // active element of form
  element;

  // active value of element of form
  elementValue;

  // active element id
  elementId;

  // class name is add on object with error
  errorClass = 'error';

  // default return class
  myReturn = true;

  // array id's of objects of forms
  toScroll;

  // elements of form to applicate validation
  formElements;

  // regex params
  regex = {
    decimal: /^(\d+(?:[.,]\d{1,2})?)$/
  }

  /**
   * @param array fields 
   * @param array rules 
   * @param string myForm 
   */
  async init(fields, rules, myForm) {

    this.myForm = myForm ?? document.querySelector('form');
    this.fields = fields;
    this.rules = rules;

    if (!this.myForm) {
      let error = 'Cannot find element <FORM>';
      console.error(error);
      throw new Error(error);
    }

    this.formElements = (this.myForm).querySelectorAll(
      'input, textarea, select, radio, hidden, checkbox, buttom'
    );


    if (!this.formElements) {
      let error = 'Cannot find elements like: input, textarea, select, radio, hidden, checkbox, buttom';
      console.error(error);
      throw new Error(error);
    }

    if (!this.rules) {
      let error = 'Cannot find Objetc rules{}';
      console.error(error);
      throw new Error(error);
    }


    this.conformityForm();
    this.resetAlerts();

    this.myReturn = true;
    this.toScroll = []

    for (const [elementId, rule] of Object.entries(rules)) {

      this.elementId = elementId;
      this.element = document.getElementById(this.elementId);
      this.rule = rule;

      if (this.element.value) {
        this.rulesAplication();
      } else if (this.rule.required) {
        this.rulesAplication();
      }
    }

    this.scrollAndFocus();


    return this.myReturn;
  }

  /**
   * organiza o array toScroll para foco correto do auto scroll
   */
  scrollAndFocus() {

    if (!this.toScroll[0]) {
      return;
    }

    console.log('linha 85', this.toScroll);
    //retorna somente os id's de this.formElements
    let formElementsIds = [...this.formElements].map(item => item.id);

    (this.toScroll).sort(function (a, b) {
      return formElementsIds.indexOf(a) - formElementsIds.indexOf(b);
    });

    scroll.scrollVerticalToElementById(this.toScroll[0], 40);

    document.getElementById(this.toScroll[0]).focus();

  }

  /**
   * add class Bootstrap into form and childs 
   * for validation success
   */
  conformityForm() {
    //var forms = document.querySelectorAll('form');
    let myForm = this.myForm;

    if (!myForm.classList.contains('needs-validation')) {
      myForm.classList.add('needs-validation');
    }

    if (!myForm.classList.contains('was-validated')) {
      myForm.classList.add('was-validated');
    }

    if (!myForm.hasAttribute("novalidate")) {
      myForm.setAttribute("novalidate", true);
    }

  }

  /**
   * reset errors alerts of fields after validation
   */
  resetAlerts() {
    (this.formElements).forEach(element => {
      element.setCustomValidity("");
    });

    // remove  a tag com msg error
    const removeTagError = document.querySelectorAll('.invalid-feedback');
    removeTagError.forEach(tag => {
      tag.remove();
    });
  }

  /**
   * aplication rules 
   */
  rulesAplication() {
    let elementId = this.elementId;
    let value = this.element.value;
    let rule = this.rule;

    //carrega a class dinamicamente
    for (const [rulesKey, rulesValue] of Object.entries(rule)) {
      this[rulesKey]({
        key: elementId,
        value: value,
        rulesKey: rulesKey,
        rulesValue: rulesValue,
      });
    }
  }


  /**
   * Add class end message erros on field whith error
   * @param {array} array 
   */
  addClassError(array) {

    this.element.classList.add(this.errorClass);
    this.element.setCustomValidity("Invalid field.");
    const tagError = document.createElement('div');
    tagError.classList.add('w-100');
    tagError.classList.add('invalid-feedback');
    tagError.innerText = array.message;

    this.element.after(tagError);

    //@param id The id of the element to scroll to.
    //@param padding Top padding to apply above element.
    //@param fps velocity scroll.
    if (!(this.toScroll).includes(array.key)) {
      (this.toScroll).push(array.key);
    }

    this.myReturn = false;
  }

  /**
   * @param {Array} array 
   */
  required(array) {
    let v = array.value.toString() ?? '';
    let k = array.key
    let message = array.message ?? 'Campo Obrigatório'
    if (!v) {
      this.element.required = true;
      this.addClassError({
        key: k,
        message: message
      });
      return;
    }
  }

  /**
   * @param {Array} array 
   */
  maxLength(array) {
    let v = array.value.toString()
    let k = array.key
    let rulesValue = array.rulesValue.toString();
    let message = array.message ?? 'Maximo de caracteres = ' + rulesValue

    if (v.length > rulesValue) {
      this.addClassError({
        key: k,
        message: message
      });
      return;
    }
  }

  /**
   * 
   * @param {Array} array 
   */
  minLength(array) {
    let v = array.value.toString()
    let k = array.key
    let rulesValue = array.rulesValue.toString();
    let message = array.message ?? 'Mínimo de caracteres = ' + rulesValue

    if (v.length < rulesValue) {
      this.addClassError({
        key: k,
        message: message
      });
      return;
    }
  }

  price(array) {

    if (!array.value) {
      return;
    }

    //let v = array.value.toLowerCase();
    let k = array.key;
    let rulesValue = array.rulesValue.toString();
    let message = array.message ?? 'O formato dos dados estão errados'
    let r = [];
    //let myRegex;

    switch (rulesValue) {
      case 'blr':
        this.decimal(array);
        break;

      default:
        break;
    }

    if (!r) {
      this.addClassError({
        key: k,
        message: message
      });
      return;
    }
  }

  /**
   * minLength: 1
   * maxLength: 9
   * regex: this.regex.decimal
   * @param {array} array 
   * @returns 
   */
  decimal(array) {

    if (!array.value) {
      return;
    }

    array.rulesValue = 1;
    this.minLength(array);

    array.rulesValue = 9;
    this.maxLength(array);

    let k = array.key;
    let message = array.message ?? 'Campo decimal ex.: 1452,22'
    let r = [];
    let myRegex;

    myRegex = new RegExp(this.regex.decimal, 'gm');
    r = myRegex.test(array.value);

    if (!r) {
      this.addClassError({
        key: k,
        message: message
      });
      return;
    }
  }

  regex(array) {
    let v = array.value
    let k = array.key
    let rulesValue = array.rulesValue.toString();
    let message = array.message ?? 'O formato dos dados estão errados'

    console.log('v', v, 'k', k, 'rulesValue', rulesValue)

    var oldVal = v;
    let regex = new RegExp(rulesValue, 'gm');

    if (!regex.test(oldVal)) {
      this.addClassError({
        key: k,
        message: message
      });
      return;
    }
  }

  /* 
  dataList() {
  var val = $("#txt").val();

  var obj = $("#colours").find("option[value='" + val + "']");

  if(obj != null && obj.length > 0)
  alert("valid");  // allow form submission
  else
  alert("invalid"); // don't allow form submission 
  }*/

  /**
   * @param {string} mail 
   */
  validationEmail(mail) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(mail);
  }

}

export default new Validation();