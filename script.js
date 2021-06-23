document.addEventListener('DOMContentLoaded', ()=>{
  // click behaviour to show specific section
  document.querySelectorAll('.navbar-links').forEach(link => {
    link.onclick = ()=>{
      // see line 27 for this function's details 
      HideAndShow(link.dataset.section)
      if(link.dataset.section === 'transactions' || link.dataset.section === 'customers' || link.dataset.section === 'new-transaction'){
        // see 38 line for this function's details
        HandleBankingSection(link.dataset.section)
      }
    }
  })

  // Default showing home view
  if(location.hash){
    // if reload than get hash and show that
    if(location.hash.substr(1) === 'transactions' || location.hash.substr(1) === 'customers' || location.hash.substr(1) === 'new-transaction'){
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
    console.log('it is customers')
  }
  else if(section === 'new-transaction'){
    console.log('it is new-transaction')
  }
}

function NewTransaction(){
  let sender,reciever,amount
  sender = document.querySelector('#sender-field').value
  reciever = document.querySelector('#reciever-field').value
  amount = document.querySelector('#amount-field').value
  return Transfer(sender,reciever,amount)
}

function GetCustomers(){
  fetch('customer.php')
  .then(response => response.json())
  .then(response => {
    return response
  })
}

function GetAccounts(){
  fetch('account.php')
  .then(response => response.json())
  .then(response => {
    return response
  })
}

function CreateCustomerTable(){
  const customers = GetCustomers(),
  accounts = GetAccounts()
  let c_id,c_name,acc_balance
  let df = new DocumentFragment()
  let tbody = document.createElement('tbody')
  tbody.id = 'customer-table-body'
  df.appendChild(tbody)
  customers.forEach(customer => {
    let customer_row = tbody.insertRow()
    txn_id = customer_row.insertCell()
    sender = customer_row.insertCell()
    reciever = customer_row.insertCell()
    amount = customer_row.insertCell()
    txn_time = customer_row.insertCell()

    c_id = customer.id
    c_name = customer.name
    c_gender = customer.gender
    c_dob = customer.dob
    c_email = customer.email
  });
}

function GetTransactions(){
  let txn_id,sender,reciever,amount,txn_time
  fetch('transaction.php')
  .then(response => response.json())
  .then(response => {
    let df = new DocumentFragment()
    let tbody = document.createElement('tbody')
    tbody.id = 'txn-table-body'
    df.appendChild(tbody)
    response.forEach(txn => {
      let txn_row = tbody.insertRow()
      txn_id = txn_row.insertCell()
      sender = txn_row.insertCell()
      reciever = txn_row.insertCell()
      amount = txn_row.insertCell()
      txn_time = txn_row.insertCell()

      txn_id.innerText = txn.id
      sender.innerText = txn.sender
      reciever.innerText = txn.reciever
      amount.innerText = txn.amount
      txn_time.innerText = txn.txn_time
    })
    document.querySelector('#txn-table').appendChild(df)
  })
}

function Transfer(sender,reciever,amount){
  fetch('newtxn.php', {
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
