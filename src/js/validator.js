export class Validator {
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
        // this.body = body;
        this.renderIn = renderIn;

        Object.keys(body).forEach((inputName, index) => {
            const element = document.querySelector(`[name="${inputName}"]`);
            if(element)
                element.addEventListener('change', () => {
                    this.validate();
                    console.log('change');
                });

            // if(!element) 
            //     throw new Error(`Validator: The input key ${inputName} declared on constructor doesn't exists as input with a name related`);
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

            if(validationFunctions.length === 0) return;

            validationFunctions.forEach(validationFn => {
                //Fill errors if validationFn is false...
                const [success, message, validationName] = validationFn(elementRef.value);
                
                if(!success) {
                    this.addError(inputName, validationName, {
                        element: elementRef,
                        success,
                        message
                    });

                    return;
                }


                this.removeError(inputName, validationName);
            });
        });

        if(this.renderIn)
            inputKeys.forEach(inputName => {
                this.renderError(inputName);
            })

        return this;
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
        if(!error) {
            this.errorMessagesNodes[inputName].remove();
            return;
        };

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

    createErrorMessage(errorContent){
        const errorMessage = document.createElement('p');
        errorMessage.textContent = errorContent;
        errorMessage.classList.add('error-input-message');
        return errorMessage;
    }
}

//Validation function rules.
export function required(value){
    const operation = value !== '' && value !== null;
    return [operation, 'This field is required', 'required'];
}