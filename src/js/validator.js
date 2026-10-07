/**
 * @todo Add support to return values based on rare input types: checkbox or select as examples.
 * @todo Add a feature to validate a field specified instead of validate the entire fields.
 */
export class Validator extends EventTarget{
    /**
     * A list with every input form tagname to determine how returns current values from them.
     */
    tagNames = ["INPUT", "SELECT", "TEXTAREA"];
    /**
     * Stores all form data in key/value object.
     * The structure is simple `key` is a valid HTMLElement with an unique id to register and `value` a array with a initial value
     * @example 
     * {
     *  name: ["someValue", [required]],
     * }
     */
    body = null;

    /**
     * A object value with all current errors after a validation() function call where the `key` is the inputName of the HTMLElement where the error occurs and `value` is a array of objects with validationName, success, message and element properties.
     */
    errors = {};
    renderIn;

    /**
     * A object with every errorMessageNodes rendered after a validation() call it stores only one by inputName key.
     * @example
     * {
     *   inputName: HTMLElementNode
     * }
     */
    errorMessagesNodes = {}; //stores every error html node by inputName: HtmlElementNode[]

    /**
     * Loads body values into the body property required structure and add listeners to rende errors in change.
     * @param {object} body A object with every HTMLElement unique name and a array with referenced functions only.
     * @param {boolean} renderIn A flag to indicate if you want errors rendered on change event or not.
     * @example
     * const form = new Validator({
     *    name = ["John", [required]]
     * })
     */
    constructor(body, renderIn = false){
        super();
        // this.body = body;
        this.renderIn = renderIn;

        Object.keys(body).forEach((inputName, index) => {
            let element = document.querySelector(`[name="${inputName}"]`);

            if(!element){
                element = document.querySelector(`#${inputName}`);
            }

            if(element && this.tagNames.includes(element.tagName))
                element.addEventListener('change', () => {
                    this.validate();
                });

            if(!element) 
               throw new Error(`Validator: The input key ${inputName} declared on constructor doesn't exists as input or id with the key ${inputName} declared`);
            body[inputName] = [element, ...body[inputName]];
        });

        this.body = body;
    }

    /**
     * Execs a validate operation calling every function reference in the fields declared in constructor class and adds every error in errors property.
     * @returns {this}
     */
    validate(){
        const inputKeys = Object.keys(this.body);

        Object.values(this.body).forEach((element, index) => {
            const [elementRef, value, validationFunctions = []] = element;
            const inputName = inputKeys[index];

            //Assigns the value to evaluate based on HTML Type.
            const inputValue = this.getValue(inputName);

            if(validationFunctions.length === 0) return;

            //Evaluate every validation function.
            validationFunctions.forEach(validationEntry => {
                //Fill errors if validationFn is false...
                const [validationFn, params = null] = validationEntry; 
                const [success, message, validationName] = validationFn(inputValue, params);
                
                if(success) {
                    this.removeError(inputName, validationName);
                    return;
                }

                this.addError(inputName, validationName, {
                        element: elementRef,
                        success,
                        message
                });
            });
        });

        if(this.renderIn)
            inputKeys.forEach(inputName => {
                this.renderError(inputName);
        });

        this.successEvent();

        return this;
    }

    /**
     *  Checks if at least one error exists in the Validator object.
     * @returns {boolean} True if form is invalid false otherwise.
     */
    invalid(){
        return Object.values(this.errors)
        .some(errors => errors.length > 0);
    }

    /**
     *  Marks all form elements as touched and performs a validation.
     * @returns {void}
     */
    markAllAsTouched(){
        Object.keys(this.body).forEach(key => {
            if(!Object.hasOwn(this.body, key)) return;
            
            const [elementRef] = this.body[key];
        })

        this.validate();
    }

    /**
     *  Render the last error based on the actual error records using a inputName to select which of them should render it.
     * @param {string} A inputName key existing in errors array.
     * @returns {any}
     */
    renderError(inputName){
        const parentError = this.errors[inputName];
        if(!this.errors[inputName]) return;

        const error = parentError[0];

        if(this.errorMessagesNodes[inputName]) 
            this.errorMessagesNodes[inputName].remove();

        if(!error) return;

        const {message, element, success, validationName} = error;

        const messageNode = this.createErrorMessage(message);
        element.after(messageNode);
        
        this.errorMessagesNodes[inputName] = messageNode;
    }

    /**
     * Adds a error entry in errors property.
     * @todo Create and append a new field if the input name passed doesn't exists yet.
     * 
     * @param {string} inputName The input name key where the entry will be added.
     * @param {string} validationName The validate name to add in error properties.
     * @param {object} error A valid Error object with element, success and message properties.
     * @returns {void}
     */
    addError(inputName, validationName, error){
        const {element, success, message} = error;
        this.errors[inputName] = [
            {
                validationName,
                element,
                success,
                message
            }
        ];
    }

    /**
     *  Removes a error entry from errors property
     * @param {string} inputName The inputName key in errors.
     * @param {string} validationName The validation name that removeError will exclude from errors property.
     * @returns {void}
     */
    removeError(inputName, validationName){
        if(!this.errors[inputName]) return;

        this.errors[inputName] = [
            ...this.errors[inputName].filter(error => error.validationName != validationName)
        ]
    }


    getErrorKeys(){
        return Object.keys(this.errors);
    }

    /**
     * Creates a HTML element to render it as error.
     * @param {string} errorContent Text to show inside the element.
     * @returns {Node} The node created ready to use.
     */
    createErrorMessage(errorContent){
        const errorMessage = document.createElement('p');
        errorMessage.textContent = errorContent;
        errorMessage.classList.add('error-input-message');
        return errorMessage;
    }

    /**
     * Appends a new value to the old array existing values in the field specified, it will be stored as a object with `value` and `id` keys.
     * @param {string} inputKey A valid field registered in teh Validator constructor
     * @param {any} valueToAdd The value that you want to append, it will be stored as value of key value.
     * @param {string | number} id A identifier to store the value to append, ot will be the value of id. 
     * @returns {object} The object successfully append.
     */
    addArrayValue(inputKey, valueToAdd, id){
        if(!Object.hasOwn(this.body, inputKey)) return;

        const bodyElement = this.body[inputKey];
        const [elementRef, value] = bodyElement;

        if(!Array.isArray(value)){
            throw new Error(`You are trying add a value in a non controlled field.`);
        }
        const readyObject = {value: valueToAdd, id: id}
        bodyElement[1] = [...value, readyObject];
        return readyObject;
    }

    /**
     * Removes an element from a controlled array based on its id.
     * @param {string} inputKey A valid field of a controlled array registered in Validator constructor. 
     * @param {string | number} indexToDelete An id of a element which you registered previously with addArrayValue() to delete.
     * @returns {void}
     */
    removeArrayValue(inputKey, indexToDelete){
        if(!Object.hasOwn(this.body, inputKey)) return;

        const bodyElement = this.body[inputKey];
        const [elementRef, value] = bodyElement;
        bodyElement[1] = [...value.filter((e) => e.id != indexToDelete)];
    }

    /**
     *  Get a actual value from elements registered in body property.
     *  if a element is a input form then it returns its actual value otherwise returns actual controlled value.
     * @param {string} inputKey A valid input key registered in Validator constructor.
     * @returns {any} The actual value of the field.
     * @todo Add support to different types of inputs like checkbox or selects.
     */
    getValue(inputKey){
        if(!Object.hasOwn(this.body, inputKey)) return;

        const bodyElement = this.body[inputKey];
        const [elementRef, value] = bodyElement;

        return this.tagNames.includes(elementRef.tagName)? elementRef.value: value; 
    }

    /**
     * Returns a object with all actual values in body respect to each field.
     * @returns {object}
     */
    mapToPlainObject(){
        let plainObject = {};
        Object.keys(this.body).forEach(key => {
            plainObject = {
                ...plainObject,
                [key]: this.getValue(key)
            }
        })
        return plainObject;
    }

    /**
     * Creates a event `validator-success` that is dispatched every time a validate() call is executed.
     * @returns {void}
     */
    successEvent(){
        const event = new CustomEvent('validator-success', {
            detail: {
                ...this.mapToPlainObject(),
                invalid: this.invalid()
            }
        });

        this.dispatchEvent(event);
    }
}

//Validation function rules.
export function required(value, params){
    const operation = value !== '' && value !== null && value.length > 0;
    return [operation, 'This field is required', 'required'];
}

export function minLength(value, minValue) {
    const operation = value.length >= minValue;
}