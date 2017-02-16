import autobind from 'autobind-decorator'
import React from 'react'
import { Button } from 'react-bootstrap'
import { Modal } from 'react-bootstrap'
import { ListGroup } from 'react-bootstrap'
import Flatpickr from 'react-flatpickr'
import { getYmdStr } from '../utils/util'
import { isSP } from '../utils/util'

import '../../sass/component/material_green.scss'
import  '../../sass/component/modal.scss'

@autobind
export default class Menu extends React.Component {

    constructor(props, context) {
        super(props, context)
        this.state = {
            showModal: false,
            selectedDate: new Date()
        }
    }

    close() {
        this.setState({showModal: false})
    }

    open() {
        this.setState({showModal: true})
    }
    
    logout() {
        location.href = '/logout'
    }

    _OnChange(date) {
        this.setState({selectedDate: date[0]})
        this.close()
        this.props.fetchDailyTweet(getYmdStr(date[0]))
    }

    render() {
        const bsSize = isSP() ? 'small' : 'large'
        return (
            <div id="menu">
                <Button className="menu-btn" bsSize={bsSize} onClick={this.open}>&#9776;</Button>

                <Modal show={this.state.showModal} onHide={this.close}>
                    <Modal.Header closeButton>
                        <Modal.Title>Menu</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <ListGroup>
                            <Flatpickr onChange={this._OnChange} options={{defaultDate: this.state.selectedDate, inline: true, enable: this.props.timelineDateList}} />
                        </ListGroup>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button onClick={this.logout}>Logout</Button>
                    </Modal.Footer>
                </Modal>
            </div>
        )
    }
}
