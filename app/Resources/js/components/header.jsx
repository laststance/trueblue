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
                        <a href="/">DailyTweet(β)</a>
                    </Navbar.Brand>
                    <Navbar.Text>
                        @{this.props.appUsername} {getKaomoji()}
                    </Navbar.Text>
                    <Nav>
                        <NavItem>
                            <div>
                                <Menu
                                    timelineDateList={this.props.timelineDateList}
                                    fetchDailyTweet={this.props.fetchDailyTweet}
                                />
                            </div>
                        </NavItem>
                    </Nav>
                </Navbar.Header>
            </Navbar>
        )
    }
}
