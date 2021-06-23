document.querySelector('body').addEventListener('DOMContentLoaded', ()=>{
  
})

/*
function CustomerInsertRow(ID, NAME, BALANCE) {
  document.querySelector('#cutomer-table-body').innerHTML = {
    `<tr id="${ID}">
      <td>${ID}</td>
      <td>${NAME}</td>
      <td>${BALANCE}</td>
    </tr>`
  } 
}
*/

function CreateCustomer(ID,NAME,BALANCE){
  
}

function CreateTransaction(ID,SENDER,RECIEVER,AMOUNT,TIME){
  
}

function NewTransaction(){
  let sender,reciever,amount
  sender = document.querySelector('#sender-field').value
  reciever = document.querySelector('#reciever-field').value
  amount = document.querySelector('#amount-field').value
  return Transfer(sender,reciever,amount)
}

function GetCustomers(){
  let c_id,c_name,c_gender,c_dob,c_email
  fetch('customer.php')
  .then(response => response.json())
  .then(response => {
    response.forEach(customer => {
      c_id = customer.id
      c_name = customer.name
      c_gender = customer.gender
      c_dob = customer.dob
      c_email = customer.email
    });
  })
}

function GetTransactions(){
  let txn_id,sender,reciever,amount,txn_time
  fetch('transaction.php')
  .then(response => response.json())
  .then(response => {
    response.forEach(txn => {
      txn_id = txn.id
      sender = txn.sender
      reciever = txn.reciever
      amount = txn.amount
      txn_time = txn.txn_time
    });
  })
}

function GetAccounts(){
  let acc_id,owner,balance
  fetch('account.php')
  .then(response => response.json())
  .then(response => {
    response.forEach(acc => {
      acc_id = acc.id
      owner = acc.owner
      balance = acc.balance
    });
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
