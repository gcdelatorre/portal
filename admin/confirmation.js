function confirmAction(action, event) {
    let message = "";

    switch (action) {
        case "submit_application":
            message = "Are you sure you want to submit your application? Make sure all details are correct.";
            break;
        case "submit_renewal":
            message = "Are you sure you want to submit your renewal proof? Double-check your uploaded file.";
            break;
        case "approve_application":
            message = "Are you sure you want to APPROVE this application?";
            break;
        case "reject_application":
            message = "Are you sure you want to REJECT this application?";
            break;
        case "approve_renewal":
            message = "Are you sure you want to APPROVE this renewal submission?";
            break;
        case "reject_renewal":
            message = "Are you sure you want to REJECT this renewal submission?";
            break;
        default:
            message = "Are you sure you want to proceed with this action?";
            break;
    }

    return confirm(message);
}
