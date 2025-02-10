<style>
.find_chemist_result {
    position: absolute;
    z-index: 1;
    background: white;
    max-height: 300px;
    overflow: auto;
    width: 100%; /* Adjust to span the width of the input */
}

/* Style the unordered list */
.find_chemist_result ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

/* Style each list item */
.find_chemist_result ul li {
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Highlight active list item */
.find_chemist_result ul li.active,
.find_chemist_result ul li:hover {
    background-color: #e6e6e6;
    font-weight: bold;
}
</style>

<script>
let currentFocusChemist = -1; // Tracks the currently focused item
$(document).ready(function() {
    $("#chemist_name").keyup(function(e){
        // Only call find_chemist if the key is not an arrow key, Enter, or Tab
        if (![37, 38, 39, 40, 13, 9].includes(e.keyCode)) { // Key codes for Left, Up, Right, Down, Enter, and Tab
            find_chemist();
        }
    });
    // Keyboard navigation function
    $("#chemist_name").on("keydown", function(e) {
        let listItems = $(".find_chemist_result ul li");

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentFocusChemist++;
            if (currentFocusChemist >= listItems.length) currentFocusChemist = 0; // Loop back to top
            addActiveChemist(listItems);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentFocusChemist--;
            if (currentFocusChemist < 0) currentFocusChemist = listItems.length - 1; // Loop back to bottom
            addActiveChemist(listItems);
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (currentFocusChemist > -1) {
                listItems[currentFocusChemist].click(); // Trigger click on the selected item
            }
        }
    });
});

function find_chemist(){
    let chemist_name = $("#chemist_name").val();
    $(".find_chemist_result").html("Loading....");

    if (chemist_name.length < 2) {
        $(".find_chemist_result").html(""); // Clear results if input is too short
        return; // Exit if fewer than 2 characters
    }

    $.ajax({
        type: "POST",
        data: {chemist_name: chemist_name},
        url: "<?= base_url()?>admin/manage_user_chemist/find_chemist",
        cache: false,
        dataType: 'json',
        success: function(response) {
            if (response.success === "1") {
                let htmlContent = '<ul>';
                response.items.forEach(item => {
                    htmlContent += `<li onclick="add_chemist('${item.chemist_id}', '${item.chemist_name}')">Name: ${item.chemist_name} - (Chemist ID: ${item.chemist_id})</li>`;
                });
                htmlContent += '</ul>';
                $('.find_chemist_result').html(htmlContent);
                currentFocusChemist = -1; // Reset focus
            } else {
                $('.find_chemist_result').text("Failed to load data.");
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('.find_chemist_result').text("Error loading data.");
        }
    });
}
function add_chemist(chemist_id, chemist_name) {
    $("#find_chemist_id").val(chemist_id);
    $("#chemist_name").val(`Name: ${chemist_name} - (Chemist ID: ${chemist_id})`);
    $(".find_chemist_result").html("");
}
function addActiveChemist(listItems) {
    listItems.removeClass("active");
    if (currentFocusChemist >= 0 && currentFocusChemist < listItems.length) {
        listItems.eq(currentFocusChemist).addClass("active");
    }
}
</script>