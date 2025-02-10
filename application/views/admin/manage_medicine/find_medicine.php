<style>
.find_medicine_result {
    position: absolute;
    z-index: 1;
    background: white;
    max-height: 300px;
    overflow: auto;
    width: 100%; /* Adjust to span the width of the input */
}

/* Style the unordered list */
.find_medicine_result ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

/* Style each list item */
.find_medicine_result ul li {
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Highlight active list item */
.find_medicine_result ul li.active,
.find_medicine_result ul li:hover {
    background-color: #e6e6e6;
    font-weight: bold;
}
</style>

<script>
let currentFocusMedicine = -1; // Tracks the currently focused item
$(document).ready(function() {
    $("#medicine_name").keyup(function(e){
        // Only call find_chemist if the key is not an arrow key, Enter, or Tab
        if (![37, 38, 39, 40, 13, 9].includes(e.keyCode)) { // Key codes for Left, Up, Right, Down, Enter, and Tab
            find_medicine();
        }
    });
    // Keyboard navigation function
    $("#medicine_name").on("keydown", function(e) {
        let listItems = $(".find_medicine_result ul li");

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentFocusMedicine++;
            if (currentFocusMedicine >= listItems.length) currentFocusMedicine = 0; // Loop back to top
            addActiveMedicine(listItems);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentFocusMedicine--;
            if (currentFocusMedicine < 0) currentFocusMedicine = listItems.length - 1; // Loop back to bottom
            addActiveMedicine(listItems);
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (currentFocusMedicine > -1) {
                listItems[currentFocusMedicine].click(); // Trigger click on the selected item
            }
        }
    });
});

function find_medicine(){
    let medicine_name = $("#medicine_name").val();
    $(".find_medicine_result").html("Loading....");

    if (medicine_name.length < 2) {
        $(".find_medicine_result").html(""); // Clear results if input is too short
        return; // Exit if fewer than 2 characters
    }

    $.ajax({
        type: "POST",
        data: {medicine_name: medicine_name},
        url: "<?= base_url()?>admin/manage_medicine/find_medicine",
        cache: false,
        dataType: 'json',
        success: function(response) {
            if (response.success === "1") {
                let htmlContent = '<ul>';
                response.items.forEach(item => {
                    htmlContent += `<li onclick="add_medicine('${item.item_code}', '${item.item_name}')">${item.item_name}</li>`;
                });
                htmlContent += '</ul>';
                $('.find_medicine_result').html(htmlContent);
                currentFocusMedicine = -1; // Reset focus
            } else {
                $('.find_chemist_find_medicine_resultresult').text("Failed to load data.");
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('.find_medicine_result').text("Error loading data.");
        }
    });
}
function add_medicine(item_code, item_name) {
    $("#find_medicine_id").val(item_code);
    $("#medicine_name").val(`${item_name}`);
    $(".find_medicine_result").html("");
}
function addActiveMedicine(listItems) {
    listItems.removeClass("active");
    if (currentFocusMedicine >= 0 && currentFocusMedicine < listItems.length) {
        listItems.eq(currentFocusMedicine).addClass("active");
    }
}
</script>