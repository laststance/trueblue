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

    describe('Timeline', () => {
        function timelineSetup() {
            const props = {
                timelineJson: {},
                timelineDateList: [],
                currentDate: '2020-12-12',
                currentIndex: 1
            }

            // TODO https://github.com/WickyNilliams/enquire.js/issues/82
            // const enzymeWrapper = shallow(<Timeline {...props}/>)

            return enzymeWrapper
        }

        it('shallow smoke test', () => {
            // const enzymeWrapper = timelineSetup()

            expect(true).toBe(true)
        })
    })
})
