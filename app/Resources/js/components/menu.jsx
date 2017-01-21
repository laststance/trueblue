import autobind from 'autobind-decorator'
import React from 'react'
import { Button } from 'react-bootstrap'
import { Modal } from 'react-bootstrap'
import { ListGroup } from 'react-bootstrap'
import { ListGroupItem } from 'react-bootstrap'
import Flatpickr from 'react-flatpickr'

import '../../sass/material_green.scss'
import  '../../sass/component/modal.scss'

@autobind
export default class Menu extends React.Component {

    constructor(props, context) {
        super(props, context);
        this.state = {
            showModal: false
        }
    }

    close() {
        this.setState({showModal: false});
    }

    open() {
        this.setState({showModal: true});
    }

    _onClick(date_str) {
        this.close();
        this.props.onClick(date_str);
    }

    _OnChange(date) {
        var date = date[0]; // TODO
        this._onClick(date.getFullYear() + '-' + ('0' + (date.getMonth() + 1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2));
        this.close();
    }

    render() {
        const self = this;
        const list_group_items = this.props.timeline_date_list.map((date_str)=> {
            return <ListGroupItem key={date_str}
                                  onClick={self._onClick.bind(this, {date_str})}>{date_str}</ListGroupItem>;
        });
        return (
            <div id="menu">
                <Button className="btn-header-right" bsSize="large" onClick={this.open}>&#9776;</Button>

                <Modal show={this.state.showModal} onHide={this.close}>
                    <Modal.Header closeButton>
                        <Modal.Title>Menu</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <ListGroup>
                            <Flatpickr onChange={this._OnChange} options={{inline: true, enable: this.props.timeline_date_list}} />
                        </ListGroup>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button onClick={this.close}>Close</Button>
                    </Modal.Footer>
                </Modal>
            </div>
        );
    }
}
