# recipe-finder
By providing a CSV file with the items in your fridge and a list of recipes we can suggest you what can you cook tonight. Check out the examples files in the repository.

## Requirements
- PHP > 5.3.3

## Installation
1. $ git clone git@github.com:naveeta25/assignment.git
2. $ cd assignment


## How to use it
find path/to/fridge.csv path/to/recipes.json



## Assumptions (edge cases)
- An item with no expiration it will never expires
- If there is a recipe with no ingredients we ignore it
- If 2 recipes share the item with the closest expiration date we pick one using rand()


