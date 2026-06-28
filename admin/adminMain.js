function loadContent(page) {
    console.log(" Loading page:", page);

    fetch(page)
        .then(response => response.text())
        .then(data => {
            let contentContainer = document.getElementById('content-container');
            if (!contentContainer) {
                console.error(' Error: content-container not found!');
                return;
            }

            //  Update page content
            contentContainer.innerHTML = data;
            console.log(" Page content loaded:", page);

            // Remove previous dynamic scripts
            document.querySelectorAll("script[data-dynamic]").forEach(script => script.remove());

            //  Create new script
            let newScript = document.createElement("script");
            newScript.setAttribute("data-dynamic", "true");

            console.log(" Checking which script to load...");

            if (page.includes("pContent.php")) {
                newScript.src = "pScript.js";
                console.log(" Assigned `pScript.js` for product management.");
            } 
            else if (page.includes("usertbl.php")) {
                newScript.src = "ascript.js";
                console.log(" Assigned `ascript.js` for user management.");
            } 
            else if (page.includes("admin_orders.php")) { 
                newScript.src = "admin_orders.js";  
                console.log(" Assigned `admin_orders.js` for order management.");
            }
            else if (page.includes("dashboard.php")) { 
                newScript.src = "dashboard.js";  
                console.log(" Assigned `admin_dashboard.js` for dashboard.");
            }
            else if (page.includes("admin_prescription.php")) { 
                newScript.src = "admin_prescription.js";  
                console.log(" Assigned `admin_prescription.js` for dashboard.");
            }
            else if (page.includes("admin_payment.php")) { 
                newScript.src = "admin_payment.js";  
                console.log(" Assigned `admin_payment.js` for payment management.");
            }
            else if (page.includes("feedback.php")) {
                newScript.src = "fscript.js";
                console.log(" Assigned fscript.js for feedback management.");
            }

            else {
                console.log(" No specific script needed for this page.");

                //  Attach general event listeners if applicable
                if (typeof attachGeneralEventListeners === "function") {
                    attachGeneralEventListeners();
                    console.log(" `attachGeneralEventListeners` executed.");
                } else {
                    console.warn(" `attachGeneralEventListeners` is not defined. Skipping...");
                }
                return;
            }

            //  Remove existing script before appending a new one
            let existingScript = document.querySelector(`script[src="${newScript.src}"]`);
            if (existingScript) {
                console.warn(" Removing existing script before reloading:", newScript.src);
                existingScript.remove();
            }

            document.body.appendChild(newScript);
            console.log(" Script added:", newScript.src);

            //  Ensure event listeners are reattached **after the script loads**
            newScript.onload = () => {
                console.log(" Script successfully loaded:", newScript.src);

                setTimeout(() => {
                    if (newScript.src.includes("pScript.js")) {
                        if (typeof rebindEventListeners === "function") {
                            rebindEventListeners();
                            console.log(" `rebindEventListeners` executed.");
                        } else {
                            console.error(" `rebindEventListeners` function is missing in pScript.js!");
                        }
                    } else if (newScript.src.includes("ascript.js")) {
                        if (typeof attachUserTableEvents === "function") {
                            attachUserTableEvents();
                            console.log(" `attachUserTableEvents` executed.");
                        } else {
                            console.error(" `attachUserTableEvents` function is missing in ascript.js!");
                        }
                    } else if (newScript.src.includes("admin_orders.js")) {
                        if (typeof initializeOrderManagement === "function") {
                            initializeOrderManagement();
                            console.log(" `initializeOrderManagement` executed.");
                        } else {
                            console.error(" `initializeOrderManagement` function is missing in admin_orders.js!");
                        }
                    } else if (newScript.src.includes("dashboard.js")) {
                        if (typeof initializeDashboard === "function") {
                            initializeDashboard();
                            console.log(" `initializeDashboard` executed.");
                        } else {
                            console.error(" `initializeDashboard` function is missing in dashboard.js!");
                        }
                    } else if (newScript.src.includes("admin_prescription.js")) {
                        if (typeof initializeDashboard === "function") {
                            initializeDashboard();
                            console.log(" `initializeDashboard` executed.");
                        } else {
                            console.error(" `initializeDashboard` function is missing in admin_prescription.js!");
                        }
                    }
                    else if (newScript.src.includes("admin_payment.js")) {
                        if (typeof initializePaymentModule === "function") {
                            initializePaymentModule();  //  Call the correct function
                            console.log(" `initializePaymentModule` executed.");
                        } else {
                            console.error(" `initializePaymentModule` function is missing in admin_payment.js!");
                        }
                    }
                    else if (newScript.src.includes("fscript.js")) {  //  Handling for fScript.js
                        if (typeof attachFeedbackEvents === "function") {
                            attachFeedbackEvents();
                            console.log(" attachFeedbackEvents executed.");
                        } else {
                            console.error(" attachFeedbackEvents function is missing in fScript.js!");
                        }
                    }
                }, 500);
            };

            newScript.onerror = () => {
                console.error(" Failed to load script:", newScript.src);
            };
        })
        .catch(error => console.error(' Error loading content:', error));
}
