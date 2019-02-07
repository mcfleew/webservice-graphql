export class Client {
  constructor(client) {
    this.id = client.id;
    this.guid = client.guid;
    this.first = client.first;
    this.last = client.last;
    this.city = client.city;
    this.street = client.street;
    this.zip = client.zip;
  }
}