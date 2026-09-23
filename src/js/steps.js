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

function managePagination(elements, actualIndex, limit){
    const {prev, next} = elements;

    const {result: resultPrev, element: elementPrev} = limitReached(prev, actualIndex, limit, "negative");
    const {result: resultNext, element: elementNext} = limitReached(next, actualIndex, limit, "positive");

    prev.disabled = false;
    next.disabled = false;

    if(resultPrev) elementPrev.disabled = true;
    if(resultNext) elementNext.disabled = true;
}

function limitReached(element, actualIndex, limit, operation = "positive"){
    calcs = {
        "negative": (actualIndex === 0),
        "positive": (actualIndex === limit),
    };
    console.log(`${operation}: ${actualIndex} - 1`);
    return {result: calcs[operation], element};
}


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