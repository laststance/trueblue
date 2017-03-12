import autobind from 'autobind-decorator'
import React from 'react'
import { connect } from 'react-redux'
import { Navbar, Nav, NavItem } from 'react-bootstrap'
import Menu from './menu.jsx'
import { getKaomoji } from '../utils/util'

@autobind
class Header extends React.Component {
    render() {
        return (
            <Navbar className="index-header">
                <Navbar.Header>
                    <Navbar.Brand>
                        <a href="/">DailyTweet(β)</a>
                    </Navbar.Brand>
                    <Navbar.Text>
                        @{this.props.username} {getKaomoji()}
                    </Navbar.Text>
                    <Nav>
                        <NavItem>
                            <div>
                                <Menu/>
                            </div>
                        </NavItem>
                    </Nav>
                </Navbar.Header>
            </Navbar>
        )
    }
}

const mapStateToProps = (state) => (
    {
        username: state.homeState.username,
        currentDate: state.homeState.currentDate
    }
)

export default connect(mapStateToProps)(Header)
