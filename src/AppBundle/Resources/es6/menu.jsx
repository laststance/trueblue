const React = require('react');
const Button = require('react-bootstrap').Button;
const Popover = require('react-bootstrap').Popover;
const Modal = require('react-bootstrap').Modal;
const ListGroup = require('react-bootstrap').ListGroup;
const ListGroupItem = require('react-bootstrap').ListGroupItem;

const Menu = React.createClass({
  getInitialState() {
    return {
      showModal: false
    };
  },

  close() {
    this.setState({ showModal: false });
  }.bind(this),

  open() {
    this.setState({ showModal: true });
  }.bind(this),

  _onClick(date_str) {
    this.close();
    this.props.onClick(date_str.date_str);
  }.bind(this),

 render() {
    const self = this;
    const list_group_items = this.props.timeline_date_list.map(function(date_str) {
      return <ListGroupItem key={date_str} onClick={self._onClick({date_str})}>{date_str}</ListGroupItem>;
    });
    return (
      <div id="menu">
        <Button className="btn-header-right" bsSize="large" onClick={this.open}>Menu</Button>

        <Modal show={this.state.showModal} onHide={this.close}>
          <Modal.Header closeButton>
            <Modal.Title>アーカイブ</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <ListGroup>
              {list_group_items}
            </ListGroup>
          </Modal.Body>
          <Modal.Footer>
            <Button onClick={this.close}>Close</Button>
          </Modal.Footer>
        </Modal>
      </div>
    );
  }
});

module.exports = Menu;
