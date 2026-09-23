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

        //Main method to manage every step and styles...
        manageStep(steps, tabs, tabIndex);

        next.addEventListener('click', () => {
            if (tabIndex == totalSteps) return;
            tabIndex += 1;

            manageStep(steps, tabs, tabIndex);
        });

        prev.addEventListener('click', () => {
            if (tabIndex == 0) return;

            tabIndex -= 1;

            manageStep(steps, tabs, tabIndex);
        });

        tabs.forEach((tabControl, index) => {
            tabControl.addEventListener('click', (e) => {
                tabIndex = index;

                manageStep(steps, tabs, tabIndex);
            })
        })
    }
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