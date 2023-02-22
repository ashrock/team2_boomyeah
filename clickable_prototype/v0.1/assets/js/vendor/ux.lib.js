function ux(selector){
    let self = (typeof selector === "string") ? document.querySelector(selector) : selector;
    
    return {
        ...self,
        html: () =>{
            return self;
        },
        on: (event, handler) => {
            self.addEventListener(event, handler);
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

document.addEventListener('input', function (event) {
	if (event.target.tagName.toLowerCase() !== 'textarea' || event.target.classList.contains("materialize-textarea")) return;
	autoExpand(event.target);
}, false);

function addAnimation(element, animation, timeout = 480){
    ux(element).addClass("animate__animated").addClass(animation);

    setTimeout(() => {
        removeAnimation(element, animation);
    }, timeout);
}

function removeAnimation(element, animation){
    ux(element).removeClass("animate__animated").removeClass(animation);
}