import {Client} from './client';
  
export class App {
  constructor() {
    this.fieldNamed = 'first';
    this.searchTerm = '';
    this.pageNumber = 1;
    this.pageNumberMax = 1;
    this.clients = [];
    this.fields = [
      {"id": "id", "name":"ID"},
      {"id":"guid", "name":"Identificateur Unique"}, 
      {"id":"first", "name":"Prénom"}, 
      {"id":"last", "name":"Nom"}, 
      {"id":"city", "name":"Ville"}, 
      {"id":"street", "name":"Rue"}, 
      {"id":"zip", "name":"Code Postal"}];
  }

  findAllClients(pageNumber) {
    var xhr = new XMLHttpRequest();
    var url = '/clients/?p='+pageNumber
    var self = this;

    xhr.open('GET', url);
    xhr.responseType = 'json';
    
    xhr.onload = function() {
      var data =  xhr.response.data;
      self.clients = [];

      data.findAllClients.forEach(function(client) {
        self.clients.push(new Client(client));
      });

      var pagination = self.clients.shift();
      self.pageNumberMax = Number(pagination.guid);
      self.pageNumber = pageNumber;
    };
    
    xhr.onerror = function() {
      console.log("Fail");
    };
    
    xhr.send();
  }

  findSomeClients(searchTerm, fieldNamed, pageNumber) {
    var xhr = new XMLHttpRequest();
    var url = '/clients/search/?s='+searchTerm+'&f='+fieldNamed+'&p='+pageNumber
    var self = this;

    xhr.open('GET', url);
    xhr.responseType = 'json';
    
    xhr.onload = function() {
      var data =  xhr.response.data;
      self.clients = [];

      data.findSomeClients.forEach(function(client) {
        self.clients.push(new Client(client));
      });

      var pagination = self.clients.shift();
      self.pageNumberMax = Number(pagination.guid);
      self.pageNumber = pageNumber;
    };
    
    xhr.onerror = function() {
      console.log("Booo");
    };
    
    xhr.send();
  }

  findAllOrSome(pageNumber) {
    if (this.searchTerm == '') {
      this.findAllClients(pageNumber);
    } else {
      this.findSomeClients(this.searchTerm, this.fieldNamed, pageNumber);
    }
  }

  resetAndfindAllClients() {
    this.searchTerm = '';
    this.fieldNamed = 'first';
    this.findAllClients(1);
  }

  resetSearchTerm() {
    this.searchTerm = '';
  }
}