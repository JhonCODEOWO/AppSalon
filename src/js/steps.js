export function steps() {
    //Get every HTML element necessary
    const steps = document.querySelectorAll('#steps .step');
    const tabs = document.querySelectorAll('#steps .tabs .tab');
    const next = document.querySelector('#steps #next');
    const prev = document.querySelector('#steps #previous');

    //Global variables to manage
    if (steps && tabs && next && prev) {
        const totalSteps = tabs.length - 1;
        let tabIndex = 0;

        managePagination({prev, next}, tabIndex, totalSteps);
        manageStep(steps, tabs, tabIndex);

        //Main method to manage every step and styles...
        next.addEventListener('click', (e) => {
            if (tabIndex == totalSteps) {
                return;
            };
            tabIndex += 1;
            managePagination({prev, next}, tabIndex, totalSteps);
            manageStep(steps, tabs, tabIndex);
        });

        prev.addEventListener('click', (e) => {
            if (tabIndex == 0) return;
            tabIndex -= 1;
            managePagination({prev, next}, tabIndex, totalSteps);
            manageStep(steps, tabs, tabIndex);
        });

        tabs.forEach((tabControl, index) => {
            tabControl.addEventListener('click', (e) => {
                tabIndex = index;
                
                managePagination({prev, next}, tabIndex, totalSteps);
                manageStep(steps, tabs, tabIndex);
            })
        })
    }
}

/**
 *  Manage button next and prev styles based on limits.
 * @param {object} elements A object with prev and next keys which values are prev and next DOM buttons.
 * @param {number} actualIndex The actual index value to check against limit
 * @param {number} limit Total of elements to compare
 * @returns {void}
 */
function managePagination(elements, actualIndex, limit){
    const {prev, next} = elements;

    const {result: resultPrev, element: elementPrev} = limitReached(prev, actualIndex, limit, "negative");
    const {result: resultNext, element: elementNext} = limitReached(next, actualIndex, limit, "positive");

    prev.disabled = false;
    next.disabled = false;

    if(resultPrev) elementPrev.disabled = true;
    if(resultNext) elementNext.disabled = true;
}

/**
 *  Checks if an element has reached its end or start bounds based.
 * @param {Element} element Element to check
 * @param {number} actualIndex
 * @param {number} limit
 * @param {"positive" | "negative"} operation Defines type of validation to check, positive checks actualIndex against limit and negative checks actualIndex against 0. 
 * @returns {object} An object with key result where True means the element reaches limit false otherwise and element which contain the element evaluated.
 */
function limitReached(element, actualIndex, limit, operation = "positive"){
    calcs = {
        "negative": (actualIndex === 0),
        "positive": (actualIndex === limit),
    };
    return {result: calcs[operation], element};
}


/**
 *  Hide/Show elements and add styles to every tab based on active value.
 * @param {NodeList} elements A node list with every HTML Element to show/hide based on active.
 * @param {NodeList} tabs A node list with every Html Element who works as tab navigator to apply styles based on active value.
 * @param {number} active A valid index value of the current section.
 * @returns {void}
 */
function manageStep(elements, tabs, active) {
    activeWorkflow = elements[active];
    activeTab = tabs[active];

    tabs.forEach((element, index) => {
        if (index != active) element.classList.remove('active');
    });

    elements.forEach((workflow, index) => {
        if (index != active) workflow.style.display = 'none';
        if (index == active) workflow.style.display = 'block';
    })

    activeWorkflow.style.display = "block";
    activeTab.classList.add('active');
}

function hideElements(elements, reverse = false) {
    elements.forEach(element => {
        element.style.display = 'none';
    });
}