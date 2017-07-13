import React from 'react'
import { shallow } from 'enzyme'
import { Header } from '../app/Resources/js/components/header'
import { Menu } from '../app/Resources/js/components/menu'

describe('components', () => {
    describe('Header', () => {
        function headerSetup() {
            const props = {
                username: 'testuser',
                currentDate: '2020-12-12'
            }

            const enzymeWrapper = shallow(<Header {...props}/>)

            return enzymeWrapper
        }

        it('shallow smoke test', () => {
            const enzymeWrapper = headerSetup()

            expect(enzymeWrapper.find('.index-header').exists()).toBe(true)
        })
    })

    describe('Menu', () => {
        function menuSetup() {
            const props = {
                timelineJson: {},
                isLogin: true,
                currentDate: '2020-12-12',
                timelineDateList: []
            }

            const enzymeWrapper = shallow(<Menu {...props}/>)

            return enzymeWrapper
        }

        it('shallow smoke test', () => {
            const enzymeWrapper = menuSetup()

            expect(enzymeWrapper.find('#menu').exists()).toBe(true)
        })
    })
})
