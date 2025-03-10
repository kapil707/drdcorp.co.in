<style>
.find_medicine_company_result {
    position: absolute;
    z-index: 1;
    background: white;
    max-height: 300px;
    overflow: auto;
    width: 100%; /* Adjust to span the width of the input */
}

/* Style the unordered list */
.find_medicine_company_result ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

/* Style each list item */
.find_medicine_company_result ul li {
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Highlight active list item */
.find_medicine_company_result ul li.active,
.find_medicine_company_result ul li:hover {
    background-color: #e6e6e6;
    font-weight: bold;
}
</style>

<script>
let currentFocusMedicineCompany = -1; // Tracks the currently focused item
$(document).ready(function() {
    $("#medicine_company_name").keyup(function(e){
        // Only call find_chemist if the key is not an arrow key, Enter, or Tab
        if (![37, 38, 39, 40, 13, 9].includes(e.keyCode)) { // Key codes for Left, Up, Right, Down, Enter, and Tab
            find_medicine_company();
        }
    });
    // Keyboard navigation function
    $("#medicine_company_name").on("keydown", function(e) {
        let listItems = $(".find_medicine_company_result ul li");

        if (e.key === "ArrowDown") {
            e.preventDefault();
            currentFocusMedicineCompany++;
            if (currentFocusMedicineCompany >= listItems.length) currentFocusMedicineCompany = 0; // Loop back to top
            addActiveMedicineCompany(listItems);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            currentFocusMedicineCompany--;
            if (currentFocusMedicineCompany < 0) currentFocusMedicineCompany = listItems.length - 1; // Loop back to bottom
            addActiveMedicineCompany(listItems);
        } else if (e.key === "Enter") {
            e.preventDefault();
            if (currentFocusMedicineCompany > -1) {
                listItems[currentFocusMedicineCompany].click(); // Trigger click on the selected item
            }
        }
    });
});

function find_medicine_company(){
    let medicine_company_name = $("#medicine_company_name").val();
    $(".find_medicine_company_result").html("Loading....");

    if (medicine_company_name.length < 2) {
        $(".find_medicine_company_result").html(""); // Clear results if input is too short
        // Dropdown ko clear karen
        $('#find_medicine_company_division').empty();

        // Default option add karen
        $('#find_medicine_company_division').append(`<option value="">Select ${medicine_company_name} Division</option>`);
        return; // Exit if fewer than 2 characters
    }

    $.ajax({
        type: "POST",
        data: {medicine_company_name: medicine_company_name},
        url: "<?= base_url()?>admin/manage_medicine/find_medicine_company",
        cache: false,
        dataType: 'json',
        success: function(response) {
            if (response.success === "1") {
                let htmlContent = '<ul>';
                response.items.forEach(item => {
                    htmlContent += `<li onclick="add_medicine_company('${item.item_code}', '${item.item_name}')">${item.item_name}</li>`;
                });
                htmlContent += '</ul>';
                $('.find_medicine_company_result').html(htmlContent);
                currentFocusMedicineCompany = -1; // Reset focus
            } else {
                $('.find_chemist_find_medicine_company_resultresult').text("Failed to load data.");
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
            $('.find_medicine_company_result').text("Error loading data.");
        }
    });
}
function add_medicine_company(item_code, item_name) {
    $("#find_medicine_company_id").val(item_code);
    $("#medicine_company_name").val(`${item_name}`);
    $(".find_medicine_company_result").html("");
    find_medicine_company_division();
    $("#company_name").val(`${item_name}`);
}
function addActiveMedicineCompany(listItems) {
    listItems.removeClass("active");
    if (currentFocusMedicineCompany >= 0 && currentFocusMedicineCompany < listItems.length) {
        listItems.eq(currentFocusMedicineCompany).addClass("active");
    }
}
/*************************************************** */
function find_medicine_company_division()
{	
	find_medicine_company_id = $("#find_medicine_company_id").val();
    medicine_company_name = $("#medicine_company_name").val();
	$.ajax({
        type: "POST",
        data: { find_medicine_company_id: find_medicine_company_id },
        url: "<?= base_url()?>admin/manage_medicine/find_medicine_company_division",
        cache: false,
        dataType: 'json',
        success: function(response) {
            if (response.success === "1") {
                // Dropdown ko clear karen
                $('#find_medicine_company_division').empty();

                // Default option add karen
                $('#find_medicine_company_division').append(`<option value="">Select ${medicine_company_name} Division</option>`);

                // Items loop kar ke options add karen
                response.items.forEach(function(item) {
                    $('#find_medicine_company_division').append(
                        `<option value="${item.item_name}">${item.item_name}</option>`
                    );
                });
            } else {
                console.error('Error:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });
}
</script>