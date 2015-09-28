var React = require('react');
var Button = require('react-bootstrap').Button;
var Popover = require('react-bootstrap').Popover;
var Modal = require('react-bootstrap').Modal;
var ListGroup = require('react-bootstrap').ListGroup;
var ListGroupItem = require('react-bootstrap').ListGroupItem;

var Menu = React.createClass({
  getInitialState() {
    return {
      showModal: false
    };
  },

  close() {
    this.setState({ showModal: false });
  },

  open() {
    this.setState({ showModal: true });
  },

  _onClick(date_str) {
    this.close();
    this.props.onClick(date_str.date_str);
  },

 render() {
    var self = this;
    var list_group_items = this.props.past_timeline_date_list.map(function(date_str) {
      return <ListGroupItem key={date_str} onClick={self._onClick.bind(this, {date_str})}>{date_str}</ListGroupItem>;
    });

    return (
      <div id="menu">
        <Button bsStyle="primary" bsSize="large" onClick={this.open}>Menu</Button>

        <Modal show={this.state.showModal} onHide={this.close}>
          <Modal.Header closeButton>
            <Modal.Title>Modal heading</Modal.Title>
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
