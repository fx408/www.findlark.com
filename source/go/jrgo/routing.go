package jrgo

import (
	"fmt"
	"reflect"
	"strings"
)

type controllerMap struct {
	controllerName string
	controllerType reflect.Type
}
type Routing struct {
	JrBase
	userMaps  []*controllerMap
	frameMaps []*controllerMap
}

/*
type RoutingInterface interface {
	Register(action string, c ControllerInterface)
	Call(path string)
}
*/
func init() {
	//JrRouting.Register("gii.default", c)
}

func (this *Routing) Register(name string, c ControllerInterface) {
	t := reflect.Indirect(reflect.ValueOf(c)).Type()
	m := &controllerMap{name, t}

	fmt.Println("->add action:", name)
	this.userMaps = append(this.userMaps, m)
}

/** 
 * 根据请求URL的路径，调用相应方法
 */
func (this *Routing) Call(path string) {

	params, length := this.ParsePath(path)
	fmt.Println("m.action:", params, length)
	for _, m := range this.frameMaps {

		if m.controllerName == params[0] {
			vc := reflect.New(m.controllerType)
			in := make([]reflect.Value, 0)
			method := vc.MethodByName("ActionIndex")
			method.Call(in)

			break
		}
	}
}

/**
 * 解析路径，分离参数及计算参数个数
 */
func (this *Routing) ParsePath(path string) ([]string, int) {
	temp := strings.Split(path, "/")
	var params []string

	for _, v := range temp {
		if strings.Trim(v, " ") != "" {
			params = append(params, v)
		}
	}

	length := len(params)

	return params, length
}
