    document.addEventListener('DOMContentLoaded', () => {
        //Get every HTML element necessary
        const steps = document.querySelectorAll('.step');
        const tabs = document.querySelectorAll('.tab');
        const next = document.querySelector('#next');
        const prev = document.querySelector('#previous');

        //Global variables to manage
        // const steps = [step1, step2, step3];
        const totalSteps = tabs.length-1;
        let tabIndex = 0;
        console.log(steps);

        //Main method to manage every step and styles...
        manageStep(steps, tabs,tabIndex);

        next.addEventListener('click', () => {
            if(tabIndex == totalSteps) return;
            tabIndex += 1;
            
            manageStep(steps, tabs,tabIndex);
        })

        prev.addEventListener('click', () => {
            if(tabIndex == 0) return;

            tabIndex -= 1;

            manageStep(steps, tabs,tabIndex);
        })
    })

    function manageStep(elements, tabs, active) {
        activeWorkflow = elements[active];
        activeTab = tabs[active];

        tabs.forEach(element => {
            if(element != active) element.classList.remove('active');
        });

        elements.forEach(workflow => {
            if(workflow != active) workflow.style.display = 'none';
            if(workflow == active) workflow.style.display = 'block';
        })
        
        activeWorkflow.style.display = "block";
        activeTab.classList.add('active');
    }

    function hideElements(elements, reverse = false) {
        elements.forEach(element => {
            element.style.display = 'none';
        });
    }