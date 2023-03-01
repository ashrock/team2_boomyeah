const REGULAR_EXPRESSIONS = {
    all_number_letters_with_uppercase   : /\w\S*/g,
    all_numbers_letters                 : /[^0-9a-z_\-]/i,
    allowed_file_formats                : /(\.jpg|\.png)$/i,
    email_format                        : /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.){1,2}[a-zA-Z]{2,}))$/, /* will also check if there is a .com on the email */
    email_format_v2                     : /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/,
    lesser_greater_than                 : /(>|<)/gi,
    name_format                         : /[@%^&!"\\\*\.,\-\:?\/\'=`{}()+_\]\|\[\><~;$#0-9]/,
    youtube_embed                       : /(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/,
    password_format                     : /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}$/
};

const CUSTOM_EVENTS = {
    "blur" : "focusout"
}
const UX_EVENTS = {
    "submit": new Event("submit", { bubbles: true }),
    "blur": new Event("blur", { bubbles: true }),
    "click": new Event("click", { bubbles: true }),
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
        serialize: (uri_encoded = false) => {
            if(self.tagName === "FORM"){
                let form_data = new FormData(self);
                let content = [];

                for (const [key, value] of form_data) {
                    content.push(`${key}=${value}`);
                }
                
                return (uri_encoded) ? encodeURI(content) : form_data;
            }
        },
        self: () => {
            return self;
        },
        after: (html_content) => {
            self.after(stringToHtmlContent(html_content));
        },
        before: (html_content) => {
            self.before(stringToHtmlContent(html_content));
        },
        replaceWith: (html_content) => {
            self.replaceWith(stringToHtmlContent(html_content));
            return ux(self);
        },
        append: (html_content) => {
            self.append(stringToHtmlContent(html_content));
            return ux(self);
        },
        prepend: (html_content) => {
            self.prepend(stringToHtmlContent(html_content));
            return ux(self);
        },
        html: (html_content = null) =>{
            if(html_content){
                self.innerHTML = html_content;
                return ux(self);
            }

            return self;
        },
        trigger: (event) => {
            let triggered_event = new Event(event, { bubbles: true });
            self.dispatchEvent(triggered_event);

            return ux(self);
        },
        remove: () => {
            self.remove();
        },
        on: (event, ...args) => {
            let handler = args[0];
            let event_selector = selector;
            
            if(typeof args[0] === "string"){
                event_selector = args[0];
                handler = args[1];
            }

            if(CUSTOM_EVENTS.hasOwnProperty(event)){
                event = CUSTOM_EVENTS[event];
                console.log("event override")
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
        data: (data_index) => {
            return self.dataset[data_index];
        },
        addClass: (element_class_name)=>{
            let element_classes = element_class_name.split(" ");
            element_classes.forEach(class_name => self.classList.add(class_name))
            return ux(self);
        },
        removeClass: (element_class_name)=>{
            let element_classes = element_class_name.split(" ");
            element_classes.forEach(class_name => self.classList.remove(class_name))
            return ux(self);
        },
        conditionalClass: (class_name, add_class = true) => {
            if(self){
                (add_class) ? self.classList.add(class_name) : self.classList.remove(class_name);
            }

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
        closest: (element_selector) => {
            return ux(self.closest(element_selector));
        },
        text: (text_value = null) => {
            if(text_value || text_value === 0){
                self.innerText = text_value;
            }

            return self.innerText;
        },
        val: (text_value = null) => {
            if(text_value || text_value === 0){
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
function stringToHtmlContent (html_content){
    let template = document.createElement('template');
    html_content = html_content.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html_content;
    return template.content.firstChild;
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
    if(typeof target_element_selector === "string"){
        ancestor_element.addEventListener(event_type, function(event){
            if (event.target && event.target.matches && event.target.matches(target_element_selector)){
                (listener_function)(event);
                
                return false;
            }
        }, false);
    } else {
        console.log("Invalid Element Selector", target_element_selector)
    }
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

function selectElementText(el, win) {
    win = win || window;
    let doc = win.document, sel, range;

    if (win.getSelection && doc.createRange) {
        sel = win.getSelection();
        range = doc.createRange();
        range.selectNodeContents(el);
        range.collapse(false);
        sel.removeAllRanges();
        sel.addRange(range);
    } else if (doc.body.createTextRange) {
        range = doc.body.createTextRange();
        range.moveToElementText(el);
        range.collapse(false);
        range.select();
    }
}

function validateEmail(email) {
    let is_email = email.match(/[^\s@]+@[^\s@]+\.[^\s@]+/gi);

    return is_email;
}

function initializeMaterializeTooltip(){
    const elems = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(elems, {
        position: "top"
    });
}

function initializeMaterializeDropdown(){
    let dropdown_elements = document.querySelectorAll('.more_action_btn');
    M.Dropdown.init(dropdown_elements, {
        alignment: 'left',
        coverTrigger: false,
        constrainWidth: false
    });
}