const FORM_SUBMIT_EVENT = new Event("submit", { bubbles: true });
const UX_EVENTS = {
    "submit": FORM_SUBMIT_EVENT
}

function ux(selector) {
    let self = (typeof selector === "string") ? document.querySelector(selector) : selector;

    return {
        ...self,
        post: async (action, post_data, callback, format = "json") =>{
            fetch(action, {
                    method: "POST",
                    body: post_data,
                })
                .then((response) => response.json())
                .then((result) => {
                    callback(result);
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        },
        serialize: () => {
            if(self.tagName === "FORM"){
                let form_data = new FormData(self);
                let content = [];

                for (const [key, value] of form_data) {
                    content.push(`${key}=${value}`);
                }
                
                return form_data;
            }
        },
        html: () =>{
            return self;
        },
        trigger: (event) => {
            if(UX_EVENTS.hasOwnProperty(event)){
                self.dispatchEvent(UX_EVENTS[event]);
            }
        },
        on: (event, ...args) => {
            let handler = args[0];
            let event_selector = selector;
            
            if(typeof args[0] === "string"){
                event_selector = args[0];
                handler = args[1];
            }

            delegateEvent(event, document, event_selector, handler);
            return ux(self);
        },
        onEach: (event, handler) =>{
            let elements = document.querySelectorAll(selector);
            elements.forEach((element, index, parent) => {
                element.addEventListener(event, (raw_event) => {handler(raw_event, index + 1)});
            });
        },
        offEach: (event, handler) =>{
            let elements = document.querySelectorAll(selector);
            elements.forEach((element, index, parent) => {
                element.removeEventListener(event, handler);
            });
        },
        addClass: (class_name)=>{
            self.classList.add(class_name);
            return ux(self);
        },
        removeClass: (class_name)=>{
            self.classList.remove(class_name);
            return ux(self);
        },
        clone: () => {
            let clone_element = self.cloneNode(true);
            return ux(clone_element);
        },
        find: (child_selector) => {
            return ux(self.querySelector(child_selector));
        },
        findAll: (child_selector) => {
            return self.querySelectorAll(child_selector);
        },
        text: (text_value) => {
            if(text_value){
                self.innerText = text_value;
            }

            return self.innerText;
        },
        val: (text_value) => {
            if(text_value){
                self.value = text_value;
            }

            return self.value;
        },
        attr: (attribute, attr_value = null) =>{
            if(attr_value){
                self.setAttribute(attribute, attr_value);
            }

            return self.getAttribute(attribute);
        }
    }
}

/*!
 * Automatically expand a textarea as the user types
 * (c) 2021 Chris Ferdinandi, MIT License, https://gomakethings.com
 * @param  {Node} field The textarea
 */
function autoExpand (field) {

	// Reset field height
	field.style.height = 'inherit';
    let field_scroll_height = field.scrollHeight;

	// Get the computed styles for the element
	let computed = window.getComputedStyle(field);
	
	// Calculate the height
	let height =
		parseFloat(computed.paddingTop) +
		field_scroll_height +
		parseFloat(computed.paddingBottom);

	field.style.height = height + 'px';
    
}
function onSubmission(){

    document.addEventListener('onsubmit', function (event) {
        console.log(event)
    }, false);
}

document.addEventListener('input', function (event) {
	if (event.target.tagName.toLowerCase() !== 'textarea' || event.target.classList.contains("materialize-textarea")) return;
	autoExpand(event.target);
}, false);

function hasClass(elem, className) {
    return elem.classList.contains(className);
}

function delegateEvent(event_type, ancestor_element, target_element_selector, listener_function){
    ancestor_element.addEventListener(event_type, function(event){
        if (event.target && event.target.matches && event.target.matches(target_element_selector)){
            (listener_function)(event);

            return false;
        }
    }, false);
}

function addAnimation(element, animation, timeout = 480){
    ux(element).addClass("animate__animated").addClass(animation);

    setTimeout(() => {
        removeAnimation(element, animation);
    }, timeout);
}

function removeAnimation(element, animation){
    ux(element).removeClass("animate__animated").removeClass(animation);
}