document.addEventListener('DOMContentLoaded', () => {
    const categoriesContainer = document.getElementById('categories');
    let activeCategoryId = null;

    // Funkcija za dohvaćanje kategorija
    async function fetchCategories() {
        try {
            const response = await fetch('http://localhost/kategorije/upravljaj.php');
            if (!response.ok) throw new Error('Greška prilikom dohvaćanja kategorija');

            const categories = await response.json();
            renderCategories(categories);
        } catch (error) {
            console.error(error.message);
        }
    }

    // Funkcija za prikaz kategorija
    function renderCategories(categories) {
        categoriesContainer.innerHTML = ''; // Očisti prethodne kategorije
        categories.forEach((category) => {
            const categoryElement = document.createElement('div');
            categoryElement.classList.add('category');
            categoryElement.setAttribute('data-category-id', category.id);

            // Prikaz preostalog budžeta u okviru renderiranja
            categoryElement.innerHTML = `
                <h3>${category.ime}</h3>
                <div class="total">${category.budget.toFixed(2)} €</div>
                <div class="remaining-budget" style="font-weight: bold;">Preostalo: <span class="remaining-budget-amount">${(category.budget - category.totalExpenses).toFixed(2)} €</span></div>
                <button class="toggle-category-btn" data-category-id="${category.id}">Povećaj kategoriju</button>
                <div class="expanded-category" style="display: none;">
                    <div class="current-budget">
                        <h4>Trenutni budžet: ${category.budget.toFixed(2)} €</h4>
                        <div class="update-budget">
                            <input type="number" class="new-budget-input" placeholder="Unesite novi budžet">
                            <button class="update-budget-btn" data-category-id="${category.id}">Ažuriraj budžet</button>
                        </div>
                    </div>
                    <div class="expenses-container"></div>
                    <div class="add-expense">
                        <input type="number" class="new-expense-input" placeholder="Unesite iznos troška">
                        <input type="text" class="new-expense-description" placeholder="Unesite opis troška">
                        <button class="add-expense-btn" data-category-id="${category.id}">Spremi trošak</button>
                    </div>
                </div>
            `;

            // Dodavanje događaja za "Povećaj kategoriju"
            categoryElement.querySelector('.toggle-category-btn').addEventListener('click', async () => {
                const categoryId = category.id;
                activeCategoryId = categoryId;
                await fetchExpenses(categoryId, categoryElement, category);
                categoryElement.querySelector('.expanded-category').style.display = 'block';
            });

            // Dodavanje događaja za ažuriranje budžeta
            categoryElement.querySelector('.update-budget-btn').addEventListener('click', async () => {
                const newBudget = parseFloat(categoryElement.querySelector('.new-budget-input').value);

                if (isNaN(newBudget) || newBudget <= 0) {
                    alert('Molimo unesite ispravan budžet!');
                    return;
                }

                await updateBudget(category.id, newBudget);
                await fetchCategories(); // Osvježi kategorije
            });

            // Dodavanje događaja za spremanje novog troška
            categoryElement.querySelector('.add-expense-btn').addEventListener('click', async () => {
                const expenseAmount = parseFloat(
                    categoryElement.querySelector('.new-expense-input').value
                );
                const expenseDescription = categoryElement.querySelector('.new-expense-description').value;

                if (isNaN(expenseAmount) || expenseAmount <= 0 || !expenseDescription.trim()) {
                    alert('Molimo unesite ispravan iznos i opis troška!');
                    return;
                }

                await saveSpending(category.id, expenseAmount, expenseDescription);
                await fetchExpenses(category.id, categoryElement, category); // Osvježi troškove nakon unosa
            });

            categoriesContainer.appendChild(categoryElement);
        });
    }

    // Funkcija za dohvat troškova za aktivnu kategoriju
    async function fetchExpenses(categoryId, categoryElement, category) {
        try {
            const response = await fetch(`http://localhost/troškovi/dohvatTroškovi.php?categoryId=${categoryId}`);
            if (!response.ok) throw new Error('Greška prilikom dohvaćanja troškova');

            const expenses = await response.json();

            let totalExpenses = 0;  // Ukupno trošenje za kategoriju
            const expensesContainer = categoryElement.querySelector('.expenses-container');
            expensesContainer.innerHTML = ''; // Očisti prethodne troškove

            if (expenses.length > 0) {
                expenses.forEach((expense) => {
                    if (expense.kolicina !== null) {
                        totalExpenses += parseFloat(expense.kolicina);  // Dodaj trošak u ukupan iznos

                        const expenseElement = document.createElement('div');
                        expenseElement.classList.add('expense');
                        expenseElement.innerHTML = `
                            <div>Trošak: ${parseFloat(expense.kolicina).toFixed(2)} €</div>
                            <div>Opis: ${expense.opis || 'Bez opisa'}</div>
                            <div>Datum: ${expense.date}</div>
                        `;
                        expensesContainer.appendChild(expenseElement);
                    }
                });
            } else {
                expensesContainer.innerHTML = '<div>Nema troškova za ovu kategoriju.</div>';
            }

            // Prikaz preostalog budžeta
            const remainingBudgetElement = categoryElement.querySelector('.remaining-budget-amount');
            const remainingBudget = category.budget - totalExpenses;
            remainingBudgetElement.textContent = remainingBudget.toFixed(2) + " €";

            // Dodajemo ukupne troškove za kasniju obradu
            category.totalExpenses = totalExpenses;

        } catch (error) {
            console.error(error.message);
        }
    }

    // Funkcija za spremanje troškova
    async function saveSpending(categoryId, spending, description) {
        try {
            const response = await fetch('http://localhost/troškovi/troškovi.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    kategorije_id: categoryId, // Umjesto "categoryId", šalje se "kategorije_id"
                    kolicina: spending,       // Umjesto "spending", šalje se "kolicina"
                    opis: description         // Umjesto "description", šalje se "opis"
                }),
            });
    
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Greška pri spremanju troška');
            }
    
            const result = await response.json();
            if (result.success) {
                alert('Trošak je uspješno spremljen');
            } else {
                alert(result.error || 'Došlo je do greške');
            }
        } catch (error) {
            console.error(error.message);
            alert('Greška: ' + error.message);
        }
    }

    // Funkcija za ažuriranje budžeta
    async function updateBudget(categoryId, newBudget) {
        try {
            const response = await fetch('http://localhost/troškovi/budgetPromjena.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: categoryId,      // Poslano sa "id"
                    budget: newBudget    // Poslano sa "budget"
                }),
            });

            if (!response.ok) throw new Error('Greška pri ažuriranju budžeta');
            const result = await response.json();
            if (result.success) {
                alert('Budžet je uspješno ažuriran');
            } else {
                alert(result.error || 'Došlo je do greške');
            }
        } catch (error) {
            console.error(error.message);
        }
    }

    // Pozivanje funkcije za dohvat kategorija
    fetchCategories();
});
