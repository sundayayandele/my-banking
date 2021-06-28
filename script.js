document.addEventListener('DOMContentLoaded', ()=>{
  // click behaviour to show specific section
  document.querySelectorAll('.navbar-links').forEach(link => {
    link.onclick = ()=>{
      // see line 27 for this function's details 
      HideAndShow(link.dataset.section)
      if(link.dataset.section === 'transactions' || link.dataset.section === 'customers' || link.dataset.section === 'new-transaction' || link.dataset.section === 'add-maoney'){
        // see 38 line for this function's details
        HandleBankingSection(link.dataset.section)
      }
    }
  })

  // Default showing home view
  if(location.hash){
    // if reload than get hash and show that
    if(location.hash.substr(1) === 'transactions' || location.hash.substr(1) === 'customers' || location.hash.substr(1) === 'new-transaction' || location.hash.substr(1) === 'add-money'){
      HandleBankingSection(location.hash.substr(1))
    }
    HideAndShow(location.hash.substr(1))
  }
  else {
    // if not any hash than it is home
    HideAndShow('home')
  }
})

// function to hide sections and display only specific one
function HideAndShow(show){
  document.querySelectorAll('.data-section').forEach(section => {
    section.style.display = 'none'
  })
  if(show){
    document.querySelector(`#${show}`).style.display = 'block'
  }
}

// to handle sections require fetching updated data
function HandleBankingSection(section){
  if(section === 'transactions'){
    GetTransactions()
  }
  else if(section === 'customers') {
    GetCustomers()
  }
  else if(section === 'new-transaction'){
    NewTransaction()
  }
  else if(section === 'add-money'){
    AddMoney()
  }
}

function NewTransaction(){
  
  fetch('customer.php?for=newtxn')
  .then(response => response.json())
  .then(result => {
    //
  })
}

function GetCustomers(){
  fetch('customer.php?all=1')
  .then(response => response.json())
  .then(customers => {
    CreateCustomerTable(customers)
  })
}

function GetAccounts(){
  fetch('account.php?all=1')
  .then(response => response.json())
  .then(accounts => {
    return accounts
  })
}

function CreateCustomerTable(customers){
  let tbody = document.querySelector('#customer-table-body')
  let c_id,c_name,c_email,acc_id,acc_balance
  let df = new DocumentFragment()
  tbody.innerHTML = ''
  df.appendChild(tbody)
  for(let customer in customers) {
    let customer_row = tbody.insertRow()
    customer_row.className = 'customer-row'

    c_id = customer_row.insertCell()
    c_name = customer_row.insertCell() 
    c_email = customer_row.insertCell()
    acc_id = customer_row.insertCell()
    acc_balance = customer_row.insertCell()

    c_id.className = 'customer-cell c-id'
    c_name.className = 'customer-cell c-name'
    c_email.className = 'customer-cell c-email'
    acc_id.className = 'customer-cell c-acc-id'
    acc_balance.className = 'customer-cell c-acc-bal'

    c_id.innerText = customers[customer]['c_id']
    c_name.innerText = customers[customer]['name']
    c_email.innerText = customers[customer]['email']
    acc_id.innerText = customers[customer]['acc_id']
    acc_balance.innerText = customers[customer]['balance']
  }
  document.querySelector('#customer-table').appendChild(df)
}

function GetTransactions(){
  let tbody = document.querySelector('#txn-table-body')
  let txn_id,sender,reciever,amount,txn_time
  let df = new DocumentFragment()
  tbody.innerHTML = ''
  df.appendChild(tbody)
  fetch('transaction.php?all=1')
  .then(response => response.json())
  .then(result => {
    for(let txn in result) {
      let txn_row = tbody.insertRow()
      txn_id = txn_row.insertCell()
      sender = txn_row.insertCell()
      reciever = txn_row.insertCell()
      amount = txn_row.insertCell()
      txn_time = txn_row.insertCell()

      txn_id.innerText = result[txn]['id']
      sender.innerText = result[txn]['sender']
      reciever.innerText = result[txn]['reciever']
      amount.innerText = result[txn]['amount']
      txn_time.innerText = result[txn]['txn_time']
    }
    document.querySelector('#txn-table').appendChild(df)
  })
}

function Transfer(){
  let sender,reciever,amount
  sender = document.querySelector('#sender-field').value
  reciever = document.querySelector('#reciever-field').value
  amount = document.querySelector('#amount-field').value
  fetch('transaction.php', {
    method: 'POST',
    body: JSON.stringify({
      sender: sender,
      reciever: reciever,
      amount: amount
    })
  })
  .then(response => response.json())
  .then(response => {
    console.log(response);
  })
}

function AddMoney(){
  let owner,amount
  owner = document.querySelector('#owner-field').value
  amount = document.querySelector('#amount-request-field').value
  return RequestMoney(id,owner,amount)
}

function RequestMoney(owner,amount){
  fetch('account.php', {
    method: 'PUT',
    body: JSON.stringify({
      owner: owner,
      amount: amount
    })
  })
  .then(response => response.json())
  .then(result => {
    console.log(result)
  })
}
