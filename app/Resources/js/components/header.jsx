import autobind from 'autobind-decorator'
import React from 'react'
import { Navbar, Nav, NavItem } from 'react-bootstrap'
import Menu from './menu.jsx'
import { getKaomoji } from '../utils/util'

@autobind
export default class Header extends React.Component {
    render() {
        return (
            <Navbar className="index-header">
                <Navbar.Header>
                    <Navbar.Brand>
                        <div>Hello @{this.props.appUsername} <span
                            className="pc-only">{getKaomoji()}</span>
                        </div>
                    </Navbar.Brand>
                    <Nav>
                        <NavItem>
                            <div>
                                <Menu onClick={this.props.getDailyJson} timelineDateList={this.props.timelineDateList}/>
                            </div>
                        </NavItem>
                    </Nav>
                </Navbar.Header>
            </Navbar>
        )
    }
}
